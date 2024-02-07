<?php
    require_once './includes/src/ventas.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/historial.css" />
</head>

<body>

	<?php require('includes/vistas/comun/cabecera.php'); ?>
	<?php require('includes/vistas/comun/sidebarIzq.php'); ?>
	<main>
		<div class="contenido">
			<?= $params['contenidoPrincipal'] ?>
            <?= es\ucm\fdi\aw\historial_ventas(); ?>
		</div>
	</main>
	<?php require('includes/vistas/comun/pie.php'); ?>

</body>
</html>