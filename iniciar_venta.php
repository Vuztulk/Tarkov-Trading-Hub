<?php
require_once __DIR__.'/includes/config.php';



$tituloPagina = 'Iniciar Venta';
$contenidoPrincipal=<<<EOF

EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_iniciar_venta.php', $params);