<?php
require_once __DIR__.'/includes/config.php';



$tituloPagina = 'Mis Ventas';
$contenidoPrincipal=<<<EOF
<body>
      <h1>Mis Ventas</h1>
  </body>
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla_mis_ventas.php', $params);