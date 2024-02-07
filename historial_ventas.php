<?php
require_once __DIR__.'/includes/config.php';



$tituloPagina = 'Historial de Ventas';
$contenidoPrincipal=<<<EOF
<body>
      <h1>Historial de Ventas</h1>
  </body>
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_historial_ventas.php', $params);