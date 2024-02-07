<?php

use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
?>

<head>
	<link rel="stylesheet" type="text/css" href="./css/sidebarIzq.css">

</head>

<div class="nav-wrapper">
	<div class="nav-menu">

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/Inventario_dorado.png" alt="inventario" class="icon-small" onclick="window.location.href='inventario.php'">
			</div>
			<div class="elem-details">
				<a aria-current="page" href="./inventario.php" class="a-inventory">Inventario</a>
			</div>
		</div>

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/Mercado_mini.png" alt="mercado" class="icon-small" onclick="window.location.href='mercado.php'">
			</div>
			<div class="elem-details">
				<details>
					<summary>Mercado</summary>
					<a href="./mercado.php" class="a-inventory">Inicio</a>
					<a href="./iniciar_venta.php" class="a-inventory">Iniciar Venta</a>
					<a href="./mis_ventas.php" class="a-inventory">Mis Ventas</a>
					<a href="./historial_ventas.php" class="a-inventory">Historial Ventas</a>
				</details>
			</div>
		</div>

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/Subasta_dorado.png" alt="subastas" class="icon-small" onclick="window.location.href='subastas.php'">
			</div>
			<div class="elem-details">
				<details>
					<summary>Subastas</summary>
					<a href="./subastas.php" class="a-inventory">Inicio</a>
				</details>
			</div>
		</div>

		<div class="elem-caja">
			<div class="elem-imagen">
				<img src="./css/img/Comunidad_mini.png" alt="comunidad" class="icon-small" onclick="window.location.href='comunidad.php'">
			</div>
			<div class="elem-details">
				<details>
					<summary>Comunidad</summary>
					<a href="./comunidad.php" class="a-inventory">Inicio</a>
				</details>
			</div>
		</div>
	</div>
</div>
<script src="./js/sidebarizq.js"></script>