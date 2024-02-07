<?php

use es\ucm\fdi\aw\clases\usuarios\Usuario;
use es\ucm\fdi\aw\clases\Item;
function mostrarTablaUsuarios(){
        $usuarios = Usuario::buscaTodos();
        echo "<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>";
        foreach ($usuarios as $usuario) {
            if($usuario->getId() != 1){
                echo "<tr>
                    <td>{$usuario->getId()}</td>
                    <td>{$usuario->getNombreUsuario()}</td>
                    <td>{$usuario->getRoles()}</td>
                    <td>

                        <form method='POST' action='./BorrarUsuario.php'>
                            <input type='hidden' name='id' value='{$usuario->getId()}'>
                            <input type='submit' value='Eliminar'>
                        </form>
                        
                    </td>
                </tr>";
            }
        }

        echo "</tbody>
    </table>";
        return null;
}

function meteItems($nombre,$rareza,$filas,$columnas){
    $ruta = RAIZ_APP . "/../";
    $target_dir = $ruta . "css/img/img_items/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    if(!Item::existeItemPorNombre($nombre)){
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            if(Item::insertarItem($nombre,$rareza,$filas,$columnas)) {
                echo '<script>alert("El item se ha insertado correctamente");</script>';
            } else {
                echo '<script>alert("Ha ocurrido un error al insertar el item");</script>';
            }        
        } else {
            echo "Ha habido un error al subir la imagen.";
        }
    }
    else{
        echo '<script>alert("El item ya existe");</script>';
    }
}

?>