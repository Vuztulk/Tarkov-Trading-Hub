<?php
require_once __DIR__.'/includes/config.php';

if (!isset($_SESSION['nombre_usuario'])) { 
  header('Location: login.php');
  exit();
}

$tituloPagina = 'Subastas';
$contenidoPrincipal=<<<EOS
  <body>
      <h1>Subastas</h1>
  </body>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_subastas.php', $params);