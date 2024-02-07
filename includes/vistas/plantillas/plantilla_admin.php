<?php
require_once './includes/src/funciones_admin.php';

use es\ucm\fdi\aw\clases\usuarios\FormularioInsertarItem;
use es\ucm\fdi\aw\clases\usuarios\FormularioAddItemUser;
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/admin.css" />
</head>

<body>
	<?php require('includes/vistas/comun/cabecera.php'); ?>
	<main>
		<div class="contenido">

			<div class="columna-izquierda">
				<div class="contenido-columna-izquierda">
					<h4>Añadir items al sistema</h4>
					<?php 
						$formularioInsertarItem = new FormularioInsertarItem();
						$formularioInsertarItem = $formularioInsertarItem->gestiona();
						echo $formularioInsertarItem;
					?>
				</div>
			</div>

			<div class="columna-centro">
				<?= $params['contenidoPrincipal'] ?>
				<div class="contenido-columna-centro">
					<h4>Añadir items al usuario</h4>
					<?php 
						$formularioAddItemUser = new FormularioAddItemUser();
						$formularioAddItemUser = $formularioAddItemUser->gestiona();
						echo $formularioAddItemUser;
					?>
				</div>
			</div>

			<div class="columna-derecha">
				<div class="contenido-columna-derecha">
					<h4>Usuarios Registrados</h4>
					<?php mostrarTablaUsuarios() ?>
				</div>
			</div>

		</div>

	</main>

	<?php require('includes/vistas/comun/pie.php'); ?>

</body>

</html>