<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item_mercado;
use es\ucm\fdi\aw\clases\Item;

class FormularioVenta extends Formulario
{
    private $id_item;
    public function __construct($id_item)
    {
        parent::__construct('formVenta', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('./iniciar_venta.php')]);
        $this->id_item = $id_item;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $items = Item::listarInventario($_SESSION['idUsuario']);

        $opcionesItems = '';

        // Generar opción seleccionada si hay un item especificado en la URL
        if (!empty($this->id_item)) {
            $itemSeleccionado = Item::getItemPorId($this->id_item);
            if ($itemSeleccionado) {
                $opcionesItems .= "<option value='" . $itemSeleccionado->getId() . "' selected>" . $itemSeleccionado->getNombre() . "</option>";
            }
        }

        foreach ($items as $item) {
            if (!empty($this->id_item) && $item->getId() == $this->id_item) {
                continue;
            }
            $opcionesItems .= "<option value='" . $item->getId() . "'>" . $item->getNombre() . "</option>";
        }

        $items_existentes = Item::getItems();
        $opcionesIntercambio = "<option value=''>- Ningún item seleccionado -</option>";
        foreach ($items_existentes as $items_existentes) {
            if (!empty($this->id_item) && $items_existentes->getId() == $this->id_item) {
                continue;
            }
            $opcionesIntercambio .= "<option value='" . $items_existentes->getNombre() . "'>" . $items_existentes->getNombre() . "</option>";
        }

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        // Generamos el formulario con los campos necesarios
        $formulario = <<<EOF
            $htmlErroresGlobales
            <fieldset>
                <legend>Venta de item</legend>

                <div>
                    <label for="id_item_a_vender">Item a vender:</label>
                    <select name="id_item_a_vender" id="id_item_a_vender">
                        $opcionesItems
                    </select>
                </div>

                <div>
                    <label for="precio_venta" class="precio-label">Precio de la venta:</label>
                    <input type="number" name="precio_venta" id="precio_venta" value="0" min="0" />
                </div>

                <div>
                    <label for="item_a_intercambiar">Item a intercambiar:</label>
                    <select name="item_a_intercambiar" id="item_a_intercambiar">
                        $opcionesIntercambio
                    </select>          
                </div>

                <div>
                    <button type="submit" name="vender">Vender item</button>
                </div>

            </fieldset>
        EOF;

        return $formulario;
    }

    protected function procesaFormulario(&$datos)
    {
        $item_a_vender = trim($datos['id_item_a_vender'] ?? '');
        $precio_venta = trim($datos['precio_venta'] ?? '');
        $item_a_intercambiar = trim($datos['item_a_intercambiar'] ?? '');
        if($precio_venta == 0 && !$item_a_intercambiar)
            echo "<script>alert('Por favor, seleccione al menos un metodo de pago para emitir la venta');</script>";
        else
            Item_mercado::venderItem($item_a_vender, $precio_venta, $item_a_intercambiar);
    }
}
