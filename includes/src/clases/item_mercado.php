<?php

namespace es\ucm\fdi\aw\clases;

require_once './includes/src/clases/Item.php';
require_once './includes/src/clases/Historial_ventas.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\Usuario;
use es\ucm\fdi\aw\clases\Item;
use es\ucm\fdi\aw\clases\Historial_ventas;

class Item_mercado
{

    public static function itemsVenta($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaVentas = [];
        $sql = "SELECT * FROM ventas_mercado WHERE id_usuario != $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaVentas[] = new Item_mercado($row['id_venta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
            }
        }
        return $listaVentas;
    }

    public static function misVentas($id_usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaVentas = [];
        $sql = "SELECT * FROM ventas_mercado WHERE id_usuario = $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaVentas[] = new Item_mercado($row['id_venta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
            }
        }
        return $listaVentas;
    }

    public static function comprarItem($item_en_venta, $id_usuario_comprador, $id_venta)
    {
        $tipo_transaccion = $item_en_venta->getTipo();

        switch ($tipo_transaccion) {
            case 'dinero':
                if (Usuario::tieneDinero($item_en_venta->getPrecio(), $id_usuario_comprador)) {
                    if (Item::añadirItemInventario($id_usuario_comprador, $item_en_venta->nombre_item)) {
                        Usuario::restaDinero($item_en_venta->getPrecio(), $id_usuario_comprador);
                        Usuario::sumaDinero($item_en_venta->getPrecio(), $item_en_venta->getId_usuario());
                        Historial_ventas::insertarVenta(
                            $id_venta,
                            Usuario::buscaPorId($item_en_venta->id_usuario)->getNombreUsuario(),
                            Usuario::buscaPorId($id_usuario_comprador)->getNombreUsuario(),
                            $item_en_venta->nombre_item,
                            $tipo_transaccion,
                            $item_en_venta->getPrecio(),
                            null
                        );
                        Item_mercado::borraPorIdVenta($id_venta);
                    } else {
                        echo '<script>alert("No hay suficiente espacio en el inventario");</script>';
                    }
                } else {
                    echo '<script>alert("No tienes suficiente dinero para hacer la compra");</script>';
                }
                break;

            case 'intercambio':
                if (Item::comprobarItemUsuario($id_usuario_comprador, $item_en_venta->nombre_intercambio)) {
                    Item::añadirItemInventario($id_usuario_comprador, $item_en_venta->nombre_item);
                    Item::añadirItemInventario($item_en_venta->id_usuario, $item_en_venta->nombre_intercambio);
                    Item::eliminarItemInventarioPorNombre($id_usuario_comprador, $item_en_venta->nombre_intercambio);
                    Historial_ventas::insertarVenta(
                        $id_venta,
                        Usuario::buscaPorId($item_en_venta->id_usuario)->getNombreUsuario(),
                        Usuario::buscaPorId($id_usuario_comprador)->getNombreUsuario(),
                        $item_en_venta->nombre_item,
                        $tipo_transaccion,
                        $item_en_venta->getPrecio(),
                        null
                    );
                    Item_mercado::borraPorIdVenta($id_venta);
                }
                break;

            case 'dual':
                if (Item::comprobarItemUsuario($id_usuario_comprador, $item_en_venta->nombre_intercambio) && Usuario::tieneDinero($item_en_venta->getPrecio(), $id_usuario_comprador)) {
                    Usuario::restaDinero($item_en_venta->getPrecio(), $id_usuario_comprador);
                    Usuario::sumaDinero($item_en_venta->getPrecio(), $item_en_venta->getId_usuario());
                    Item::añadirItemInventario($id_usuario_comprador, $item_en_venta->nombre_item);
                    Item::añadirItemInventario($item_en_venta->id_usuario, $item_en_venta->nombre_intercambio);
                    Item::eliminarItemInventarioPorNombre($id_usuario_comprador, $item_en_venta->nombre_intercambio);
                    Historial_ventas::insertarVenta(
                        $id_venta,
                        Usuario::buscaPorId($item_en_venta->id_usuario)->getNombreUsuario(),
                        Usuario::buscaPorId($id_usuario_comprador)->getNombreUsuario(),
                        $item_en_venta->nombre_item,
                        $tipo_transaccion,
                        $item_en_venta->getPrecio(),
                        null
                    );
                    Item_mercado::borraPorIdVenta($id_venta);
                }
                break;
        }
    }

    public static function venderItem($id_item_a_vender, $precio_venta, $item_a_intercambiar)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        do {
            $id_venta = rand(1, 1000); // Generamos un id aleatorio para el item
            $comprobacion = sprintf("SELECT id_venta FROM ventas_mercado WHERE id_venta = %d", $id_venta);
            $result = $conn->query($comprobacion);
        } while ($result->num_rows > 0); // Si el id ya existe en la tabla, lo volvemos a intentar

        $item = Item::getItemPorId($id_item_a_vender);
        $nombre = $item->getNombre();
        $id_usuario = $_SESSION['idUsuario'];
        $tipo_venta = "";
        if ($precio_venta != 0 && $item_a_intercambiar) {
            $tipo_venta = "dual";
        } elseif ($precio_venta != 0 && !$item_a_intercambiar) {
            $tipo_venta = "dinero";
        } elseif ($precio_venta == 0 && $item_a_intercambiar) {
            $tipo_venta = "intercambio";
        }

        $sql = sprintf(
            "INSERT INTO ventas_mercado (id_venta, nombre_item, id_usuario, tipo, precio, nombre_intercambio) VALUES (%d, '%s', %d, '%s', %f, '%s')",
            $id_venta,
            $nombre,
            $id_usuario,
            $tipo_venta,
            $precio_venta,
            $item_a_intercambiar
        );

        if (!$conn->query($sql)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        $sql = sprintf("DELETE FROM inventario_usuario WHERE id_usuario='%s' AND id_inv='%d'", $conn->real_escape_string($_SESSION['idUsuario']), $item->getId());
        $result = $conn->query($sql);
        return true;
    }

    public static function borraPorIdVenta($id_venta)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM ventas_mercado WHERE id_venta = %d", $id_venta);
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function getItemPorNombre($nombreItem)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM ventas_mercado WHERE nombre_item='$nombreItem'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $item_mercado = new Item_mercado($row['id_venta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
            return $item_mercado;
        }
        return null;
    }

    public static function getItemPorIdVenta($idVenta)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM ventas_mercado WHERE id_venta = $idVenta";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $item_mercado = new Item_mercado($row['id_venta'], $row['nombre_item'], $row['id_usuario'], $row['tipo'], $row['precio'], $row['nombre_intercambio']);
            return $item_mercado;
        }
        return null;
    }

    public static function solapaInventario($idUsuario, $itemMovido)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf("SELECT * FROM inventario_usuario WHERE id_usuario='%s'", $conn->real_escape_string($idUsuario));
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $inventario = Item::listarInventario($idUsuario);

            $filas = 10;
            $columnas = 25;
            $nuevoItemFilas = $itemMovido->getFilas();
            $nuevoItemColumnas = $itemMovido->getColumnas();

            $fila = 1;
            $columna = 1;

            for ($i = 1; $i <= $filas - $nuevoItemFilas + 1; $i++) {
                for ($j = 1; $j <= $columnas - $nuevoItemColumnas + 1; $j++) {
                    $ocupado = false;
                    foreach ($inventario as $item) {
                        if (
                            $item->getX() <= $fila + $nuevoItemFilas - 1 &&
                            $item->getX() + $item->getFilas() - 1 >= $fila &&
                            $item->getY() <= $columna + $nuevoItemColumnas - 1 &&
                            $item->getY() + $item->getColumnas() - 1 >= $columna
                        ) {
                            $ocupado = true;
                            break;
                        }
                    }
                    if (!$ocupado) {
                        return array($fila, $columna);
                    }

                    // Si se ha alcanzado la última fila, pasar a la siguiente columna
                    if ($fila == $filas) {
                        $fila = 1;
                        $columna++;
                    } else {
                        $fila++;
                    }
                }
            }
        }
        return false;
    }



    private $id_venta;
    private $nombre_item;
    private $id_usuario;
    private $tipo;
    private $precio;
    private $nombre_intercambio;
    private function __construct($id_venta, $nombre_item, $id_usuario, $tipo, $precio, $nombre_intercambio)
    {
        $this->id_venta = $id_venta;
        $this->nombre_item = $nombre_item;
        $this->id_usuario = $id_usuario;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->nombre_intercambio = $nombre_intercambio;
    }
    public function getId()
    {
        return $this->id_venta;
    }
    public function getNombreItem()
    {
        return $this->nombre_item;
    }

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getNombre_intercambio()
    {
        return $this->nombre_intercambio;
    }

    public function getNombreUsuario($id)
    {
        return Usuario::buscaNombreUsuario($id);
    }
}
