<?php

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $params['tituloPagina'] ?></title>
	<link rel="stylesheet" href="./css/registro.css" />
</head>

<body>

	<div class="container">
		<img src="./css/img/Logo.png" alt="Logo" onclick="window.location.href='index.php'"/>
        <h1>Nuevo usuario</h1>
		<?= $params['contenidoPrincipal'] ?>
	</div>
	<?php require('includes/vistas/comun/pie.php');?>
</body>

</html>