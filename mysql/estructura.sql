-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-04-2023 a las 11:28:14
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estructura`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_ventas`
--

CREATE TABLE `historial_ventas` (
  `id_venta` int(20) NOT NULL,
  `nombre_vendedor` varchar(50) NOT NULL,
  `nombre_comprador` varchar(50) NOT NULL,
  `nombre_item` varchar(50) NOT NULL,
  `tipo_venta` varchar(50) NOT NULL,
  `precio` int(20) NOT NULL,
  `intercambio` varchar(50) NOT NULL,
  `fecha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_ventas`
--

INSERT INTO `historial_ventas` (`id_venta`, `nombre_vendedor`, `nombre_comprador`, `nombre_item`, `tipo_venta`, `precio`, `intercambio`, `fecha`) VALUES
(54, 'user', 'admin', 'casco', 'dinero', 10, '', '28-04-2023 20:49'),
(65, 'admin', 'user', 'casco', 'dinero', 100, '', 'hoy'),
(989, 'user', 'admin', 'plate_carrier', 'intercambio', 0, '', '28-04-2023 21:08'),
(990, 'user', 'admin', 'plate_carrier', 'dual', 100, '', '28-04-2023 21:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_usuario`
--

CREATE TABLE `inventario_usuario` (
  `id_usuario` int(255) NOT NULL,
  `id_inv` int(10) NOT NULL,
  `nombre_item` varchar(255) NOT NULL,
  `pos_x` int(10) NOT NULL,
  `pos_y` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario_usuario`
--

INSERT INTO `inventario_usuario` (`id_usuario`, `id_inv`, `nombre_item`, `pos_x`, `pos_y`) VALUES
(1, 0, 'ledx', 2, 3),
(1, 160, 'plate_carrier', 8, 5),
(1, 165, 'ledx', 1, 3),
(19, 172, 'casco', 1, 1),
(1, 177, 'casco', 7, 1),
(1, 263, 'casco', 1, 4),
(1, 352, 'ledx', 6, 4),
(1, 378, 'plate_carrier', 1, 6),
(1, 560, 'moonshine', 3, 4),
(1, 568, 'plate_carrier', 3, 1),
(1, 622, 'casco', 1, 1),
(19, 676, 'ledx', 1, 1),
(1, 921, 'plate_carrier', 13, 3),
(1, 931, 'moonshine', 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `nombre` varchar(255) NOT NULL,
  `rareza` varchar(255) NOT NULL,
  `filas` int(10) NOT NULL,
  `columnas` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`nombre`, `rareza`, `filas`, `columnas`) VALUES
('casco', 'comun', 2, 2),
('ledx', 'Legendario', 1, 1),
('moonshine', 'Epico', 2, 1),
('plate_carrier', 'raro', 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(255) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `rol` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `capacidad_inventario` int(255) NOT NULL,
  `dinero` int(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `rol`, `email`, `capacidad_inventario`, `dinero`, `password`) VALUES
(1, 'admin', 1, 'admin@gmail.com', 100, 1900, '$2y$10$pLpmHUCbJ5P6D0qkiyg.P.4pEkFfLdrNWTmORAwasXifrVrLOnTFW'),
(19, 'user', 3, '', 40, 2100, '$2y$10$Nh/z2P.F5x7uVuO//me0EeWk7RSaTbK8Pw98du/46x6aNZDyPhfY.'),
(20, 'usuario', 3, '', 40, 500, '$2y$10$zUHArWhV5nsu5ZR9WVX3T.2DSKAbrKIl0es98WKowlUoyWxHXPSNq');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_mercado`
--

CREATE TABLE `ventas_mercado` (
  `id_venta` int(10) NOT NULL,
  `id_usuario` int(255) NOT NULL,
  `nombre_item` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `precio` int(255) NOT NULL,
  `nombre_intercambio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- Indices de la tabla `inventario_usuario`
--
ALTER TABLE `inventario_usuario`
  ADD UNIQUE KEY `id_inv` (`id_inv`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas_mercado`
--
ALTER TABLE `ventas_mercado`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ventas_mercado`
--
ALTER TABLE `ventas_mercado`
  MODIFY `id_venta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=991;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventario_usuario`
--
ALTER TABLE `inventario_usuario`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nombre_item` FOREIGN KEY (`nombre_item`) REFERENCES `items` (`nombre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas_mercado`
--
ALTER TABLE `ventas_mercado`
  ADD CONSTRAINT `fk_id_usuario_mer` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
