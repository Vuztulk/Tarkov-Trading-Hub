<?php
    require_once './includes/src/ventas.php';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/venta.css" />
</head>

<body>

	<?php require('includes/vistas/comun/cabecera.php'); ?>
	<?php require('includes/vistas/comun/sidebarIzq.php'); ?>
	<main>
		<div class="contenido">
			<?= $params['contenidoPrincipal'] ?>
            <?php 
                es\ucm\fdi\aw\inicia_venta(); 
            ?>
		</div>
	</main>
	<?php require('includes/vistas/comun/pie.php'); ?>

</body>
</html>