<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;
use es\ucm\fdi\aw\clases\Item;

class FormularioEliminarVenta extends Formulario
{
    private $id_venta;

    public function __construct($id_venta)
    {
        parent::__construct('formEliminarVenta', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./mis_ventas.php'),
        ]);
        $this->id_venta = $id_venta;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS
            <input type="hidden" name="id_venta" value="{$this->id_venta}">
            <button class="btn-eliminar-venta" type="submit" name="EliminarVenta">Eliminar Venta</button>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        $id = trim($datos['id_venta'] ?? '');
        if(Item::añadirItemInventario($_SESSION['idUsuario'], Item_mercado::getItemPorIdVenta($id)->getNombreItem()));
            Item_mercado::borraPorIdVenta($id);

    }
}






