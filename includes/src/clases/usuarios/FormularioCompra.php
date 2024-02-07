<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;

class FormularioCompra extends Formulario
{
    private $item;
    private $id_usuario_comprador;
    private $id_venta;

    public function __construct($item, $id_usuario_comprador, $id_venta)
    {
        parent::__construct('formCompra', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./mercado.php'),
        ]);
        $this->item = $item;
        $this->id_usuario_comprador = $id_usuario_comprador;
        $this->id_venta = $id_venta;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS
        <input type="hidden" name="id_usuario_comprador" value="{$this->id_usuario_comprador}">
        <input type="hidden" name="id_venta" value="{$this->id_venta}">
        <button class="btn" type="submit" name="comprar">Comprar</button>
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        if (isset($datos['comprar'])) {
            Item_mercado::comprarItem($this->item, $this->id_usuario_comprador,$this->id_venta);
        }
    }
}
