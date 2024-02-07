<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\clases\Item;

class FormularioAddItemUser extends Formulario
{

    public function __construct()
    {
        parent::__construct('formAddItemUser', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./admin.php'),
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $items = Item::getItems();
        $options_items = "";
        foreach ($items as $item) {
            $options_items .= "<option value='{$item->getNombre()}'>{$item->getNombre()}</option>";
        }

        $usuarios = Usuario::buscaTodos();
        $options_users = "";
        foreach ($usuarios as $user) {
            $options_users .= "<option value='{$user->getId()}'>{$user->getNombreUsuario()}</option>";
        }


        $camposFormulario = <<<EOS
        <div class="formulario-insertar-item-user">
        
            <label for="item">Item</label>
            <select name="item" id="item">
                {$options_items}
            </select><br>
            
            <label for="usuario">Usuario</label>
            <select name="usuario" id="usuario">
                {$options_users}
            </select>  
            
        </div>

        <input type="submit" value="Añadir" class ="btn-añadir">
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos)
    {
        $user = filter_var(trim($datos['usuario'] ?? ''), FILTER_SANITIZE_STRING);
        $item = filter_var(trim($datos['item'] ?? ''), FILTER_SANITIZE_STRING);

        Item::añadirItemInventario($user, $item);
        echo '<script>alert("Item añadido al usuario");</script>';
    }
}
