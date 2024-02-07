<?php
require_once __DIR__.'/includes/config.php';

if (!isset($_SESSION['nombre_usuario'])) { 
  header('Location: login.php');
  exit();
}

$tituloPagina = 'Inventario';
$contenidoPrincipal = '';

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_inventario.php', $params);
