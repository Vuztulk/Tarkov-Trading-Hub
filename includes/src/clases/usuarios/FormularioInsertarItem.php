<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
require_once './includes/src/funciones_admin.php';

class FormularioInsertarItem extends Formulario
{

    public function __construct()
    {
        parent::__construct('formInsertarItem', [
            'method' => 'POST',
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('./admin.php'),
            'enctype' => 'multipart/form-data'
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS
        <div class="formulario-nuevo-item">
            
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre"><br>
        
            <label for="rareza">Tipo</label>
            <select id="rareza" name="rareza">
                <option value="Comun">Comun</option>
                <option value="Raro">Raro</option>
                <option value="Epico">Epico</option>
                <option value="Legendario">Legendario</option>
            </select><br>
        
            <label for="filas">Filas</label>
            <select id="filas" name="filas">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br>

            <label for="columnas">Columnas</label>
            <select id="columnas" name="columnas">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br>

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" name="imagen"><br>
        
        </div>
        
        <input type="submit" value="Añadir" class ="btn-añadir">
        EOS;
        return $camposFormulario;
    }

    protected function procesaFormulario(&$datos){

        $nombre = filter_var(trim($datos['nombre'] ?? ''), FILTER_SANITIZE_STRING);
        $rareza = filter_var(trim($datos['rareza'] ?? ''), FILTER_SANITIZE_STRING);
        $filas = filter_var(trim($datos['filas'] ?? ''), FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
        $columnas = filter_var(trim($datos['columnas'] ?? ''), FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        meteItems($nombre, $rareza, $filas, $columnas);
    }
    
}
