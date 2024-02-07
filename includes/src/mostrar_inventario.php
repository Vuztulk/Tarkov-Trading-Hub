<?php

use es\ucm\fdi\aw\clases\Item;

function mostrarInventario($idUsuario) {
    $inventario = Item::listarInventario($idUsuario);

    if (empty($inventario)) {
        echo "El inventario está vacío.";
        return;
    }

    echo "<ul id='inventario' class='inventario'></ul>";
    
}


?>
