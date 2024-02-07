<?php

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" type="text/css" href="<?= $params['app']->resuelve('./css/index.css') ?>" />
</head>

	<?php require('includes/vistas/comun/cabecera.php'); ?>

<body>
	<div id="contenedor">
		<?php

		?>
		<main>
			<article>
				<?= $params['contenidoPrincipal'] ?>
			</article>
		</main>
		<?php

		?>
	</div>
</body>

</html>