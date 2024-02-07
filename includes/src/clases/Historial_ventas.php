<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\usuarios\Usuario;

class Historial_ventas
{
    public static function ventasUsuario($id_usuario)
    {
        $nombre = Usuario::buscaPorId($id_usuario)->getNombreUsuario();
        $conn = Aplicacion::getInstance()->getConexionBd();
        $listaVentas = [];
        $sql = "SELECT * FROM historial_ventas WHERE nombre_vendedor = '$nombre'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listaVentas[] = new Historial_ventas($row['id_venta'], $row['nombre_vendedor'], $row['nombre_comprador'], $row['nombre_item'], $row['tipo_venta'], $row['precio'], $row['intercambio'], $row['fecha']);
            }
        }
        return $listaVentas;
    }

    public static function insertarVenta($id_venta,$nombre_vendedor, $nombre_comprador, $nombre_item, $tipo_venta, $precio, $intercambio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $fecha = date('d-m-Y H:i');
        $sql = "INSERT INTO historial_ventas (id_venta, nombre_vendedor, nombre_comprador, nombre_item, tipo_venta, precio, intercambio, fecha) 
                VALUES ('$id_venta', '$nombre_vendedor', '$nombre_comprador', '$nombre_item', '$tipo_venta', '$precio', '$intercambio', '$fecha')";
        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    private $id_venta;
    private $nombre_vendedor;
    private $nombre_comprador;
    private $nombre_item;
    private $tipo_venta;
    private $precio;
    private $intercambio;
    private $fecha;

    private function __construct($id_venta, $nombre_vendedor, $nombre_comprador, $nombre_item, $tipo_venta, $precio, $intercambio, $fecha)
    {
        $this->id_venta = $id_venta;
        $this->nombre_vendedor = $nombre_vendedor;
        $this->nombre_comprador = $nombre_comprador;
        $this->nombre_item = $nombre_item;
        $this->tipo_venta = $tipo_venta;
        $this->precio = $precio;
        $this->intercambio = $intercambio;
        $this->fecha = $fecha;
    }

    public function getIdVenta()
    {
        return $this->id_venta;
    }

    public function getNombreVendedor()
    {
        return $this->nombre_vendedor;
    }

    public function getNombreComprador()
    {
        return $this->nombre_comprador;
    }

    public function getNombreItem()
    {
        return $this->nombre_item;
    }

    public function getTipoVenta()
    {
        return $this->tipo_venta;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getIntercambio()
    {
        return $this->intercambio;
    }

    public function getFecha()
    {
        return $this->fecha;
    }


}
