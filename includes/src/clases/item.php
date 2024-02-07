<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;

class Item
{
    public static function listarInventario($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $inventario = [];
        $sql = sprintf("SELECT *
                        FROM inventario_usuario ui 
                        JOIN items i ON ui.nombre_item = i.nombre 
                        WHERE ui.id_usuario='%s'", $conn->real_escape_string($idUsuario));
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $inventario[] = new Item($row['id_inv'], $row['nombre'], $row['rareza'], $row['filas'], $row['columnas'], $row['pos_x'], $row['pos_y']);
            }
        }
        return $inventario;
    }

    public static function añadirItemInventario($idUsuario, $nombre_item)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        do {
            $id_inv = rand(1, 1000); // Generamos un id aleatorio para el item
            $comprobacion = sprintf("SELECT id_inv FROM inventario_usuario WHERE id_inv = %d", $id_inv);
            $result = $conn->query($comprobacion);
        } while ($result->num_rows > 0);

        $posicion = Item_mercado::solapaInventario($idUsuario, self::getItemPorNombre($nombre_item));
        //$posicion = [1, 1];

        $sql = sprintf(
            "INSERT INTO inventario_usuario (id_usuario, id_inv, nombre_item, pos_x, pos_y) VALUES ('%d', '%d','%s','%d','%d' )",
            $conn->real_escape_string($idUsuario),
            $id_inv,
            $nombre_item,
            $posicion[0],
            $posicion[1]
        );
        $result = $conn->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public static function actualizarItemInventario($idUsuario, $item)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf(
            "UPDATE inventario_usuario 
                         SET pos_x='%s', pos_y='%s'
                         WHERE id_usuario='%s' AND id_inv='%s'",
            $conn->real_escape_string($item['x']),
            $conn->real_escape_string($item['y']),
            $conn->real_escape_string($idUsuario),
            $conn->real_escape_string($item['id'])
        );
        $result = $conn->query($sql);
        if ($result) {
            echo "Item actualizado correctamente en la base de datos";
        } else {
            echo "Error al actualizar el item en la base de datos";
        }
    }

    public static function eliminarItemInventario($idUsuario, $item)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf("DELETE FROM inventario_usuario WHERE id_usuario='%s' AND id_inv='%s'", $conn->real_escape_string($idUsuario), $conn->real_escape_string($item['id']));
        $result = $conn->query($sql);
        if ($result) {
            echo "Item eliminado correctamente de la base de datos";
        } else {
            echo "Error al eliminar el item de la base de datos";
        }
    }

    public static function eliminarItemInventarioPorNombre($idUsuario, $nombre_item)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf("DELETE FROM inventario_usuario WHERE id_usuario='%s' AND nombre_item='%s' LIMIT 1", $conn->real_escape_string($idUsuario), $nombre_item);
        $result = $conn->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function getItemPorNombre($nombre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf(
            "SELECT * FROM items WHERE nombre='%s'",
            $conn->real_escape_string($nombre)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Item(null, $row['nombre'], $row['rareza'], $row['filas'], $row['columnas'], null, null);
        } else {
            return null;
        }
    }

    public static function existeItemPorNombre($nombre) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf(
            "SELECT * FROM items WHERE nombre='%s'",
            $conn->real_escape_string($nombre)
        );
        $result = $conn->query($sql);
        return ($result->num_rows > 0);
    }    

    public static function getItemPorId($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf(
            "SELECT * FROM inventario_usuario WHERE id_inv='%d'",
            $conn->real_escape_string($id)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Item($row['id_inv'], $row['nombre_item'], null, null, null, $row['pos_x'], $row['pos_y']);
        } else {
            return null;
        }
    }

    public static function getItems()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM items";
        $result = $conn->query($sql);
        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = new Item(null, $row['nombre'], $row['rareza'], $row['filas'], $row['columnas'], null, null);
                $items[] = $item;
            }
        }
        return $items;
    }

    public static function insertarItem($nombre, $rareza, $filas, $columnas)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO items (nombre, rareza, filas, columnas) VALUES ('$nombre', '$rareza', '$filas', '$columnas')";
        if ($conn->query($sql) === TRUE) {
            $id = $conn->insert_id;
            $item = new Item($id, $nombre, $rareza, $filas, $columnas, null, null);
            return $item;
        } else {
            error_log($conn->error);
            return false;
        }
    }

    public static function comprobarItemUsuario($id_usuario, $nombre_item)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = sprintf(
            "SELECT * FROM inventario_usuario WHERE id_usuario ='%d' AND nombre_item='%s'",
            $conn->real_escape_string($id_usuario),
            $conn->real_escape_string($nombre_item)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    private $id;
    private $nombre;
    private $rareza;
    private $filas;
    private $columnas;
    private $pos_x;
    private $pos_y;
    private function __construct($id, $nombre, $rareza, $filas, $columnas, $pos_x, $pos_y)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->rareza = $rareza;
        $this->filas = $filas;
        $this->columnas = $columnas;
        $this->pos_x = $pos_x;
        $this->pos_y = $pos_y;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getRareza()
    {
        return $this->rareza;
    }
    public function getFilas()
    {
        return $this->filas;
    }
    public function getColumnas()
    {
        return $this->columnas;
    }
    public function getX()
    {
        return $this->pos_x;
    }
    public function getY()
    {
        return $this->pos_y;
    }
}
