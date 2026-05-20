-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Servidor: db5014807919.hosting-data.io
-- Tiempo de generación: 07-07-2025 a las 21:27:23
-- Versión del servidor: 8.0.36
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbs12303258`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avales`
--

CREATE TABLE `avales` (
  `id` int NOT NULL,
  `folioAval` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nombreAval` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidoAval` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `docIdentAval` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telAval` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `correoAval` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `dirAval` varchar(350) COLLATE utf8mb4_general_ci NOT NULL,
  `id_status` int NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avales`
--

INSERT INTO `avales` (`id`, `folioAval`, `nombreAval`, `apellidoAval`, `docIdentAval`, `telAval`, `correoAval`, `dirAval`, `id_status`, `fecha_registro`) VALUES
(1, '252136761', 'Juanita ', 'Perez Contrera', 'DFHHD44DFH', '765787678', 'juanitag@gmail.com', 'Av.Reforma #202', 1, '2025-05-29 08:47:38'),
(2, '115634299', 'Rosa', 'Gonzalez Cabrera', 'TEWRTDFD44ZZX', '3445999', 'rosa@gmail.com', 'Calle 50 x  59', 1, '2025-05-29 08:59:57'),
(3, '414473278', 'Ernesto', 'De la Cruz', 'HHFDFDFD', '534666', 'ernesto@gmail.com', 'Calle 27 x 16 y 14', 1, '2025-05-29 09:00:57'),
(5, '86866888', 'Francisco', 'Ek Balam', 'JDFBFBUJW', '12345', 'fr@gmail.com', 'Progreso, Yucatan', 1, '2025-06-04 14:53:46'),
(6, '870815227', 'Oliver', 'Hernandez Peresz', 'ODFDSSDGRGTR', '99852365220', 'ol@gmail.com', 'Villa Hermosa, Tabasco', 1, '2025-06-04 17:56:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `folioClient` int NOT NULL,
  `nombreClient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidoClient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `docIdentClient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telClient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `correoClient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `dirClient` varchar(350) COLLATE utf8mb4_general_ci NOT NULL,
  `id_status` int NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `folioClient`, `nombreClient`, `apellidoClient`, `docIdentClient`, `telClient`, `correoClient`, `dirClient`, `id_status`, `fecha_registro`) VALUES
(1, 910809716, 'Benito ', 'Juarez Azcorra', 'BDFERFGREERG', '258962356', 'benito@gmail.com', 'C. 81, No.431, CDMX', 1, '2025-05-28 18:39:16'),
(2, 249738630, 'Jesus', 'De Nazaret', 'ERTERERGGGER1', '3532521', 'jesus@belen.com', 'SMZA.97 LT.19 MZ.11 CALLE TONINA BODEGA N.1', 1, '2025-05-28 18:46:32'),
(3, 749901161, 'Mario', 'Molina Pacheco', 'BRWFGRG3SD', '3532523', 'mario@gmail.com', 'Calle 27 x 16 y 14', 1, '2025-05-28 18:50:47'),
(4, 974679172, 'Juan Francisco', 'Zapata Fiel', 'HHDFHYTBFDASASD', '353252', 'juan@gmail.com', 'Calle 27 x 16 y 14sss', 1, '2025-06-03 15:43:04'),
(5, 885858896, 'Sandra', 'Gonazalo Mendez', 'JDJFOPPKREEE', '12345', 'sandra@gmail.com', 'Mexico', 1, '2025-06-04 09:28:14'),
(9, 676876588, 'Emmanuel ', 'Poot M', 'EFRFDYHDFH', '6549684', 'e@gmail.com', 'Mexico', 1, '2025-06-04 09:47:32'),
(10, 898565445, 'Jorge', 'Noh', 'fdhgfdhdfh', '444', 'j@gmail-com', 'ddd', 1, '2025-06-04 09:47:32'),
(11, 989899, 'Alisson', 'Zapata', 'yuuyuyuyuy', '6549684', 'a@gmail.com', 'Mexico', 1, '2025-06-04 09:54:43'),
(12, 525588888, 'Sergio', 'Pacheco', 'FHFDHDFH', '9595258566', 'sergio@gmail.com', 'Calle 30 Dzilam Gonzalez', 1, '2025-06-04 17:54:34'),
(13, 167546026, 'Gustavo', 'Diaz Ordaz', 'YYGFFFRYYY', '9998955263', 'gus@gmail.com', 'CDMX', 1, '2025-06-04 17:55:50'),
(14, 389661926, 'jimena andrea', 'salas canul', 'ine', '9862365632132', 'jimena@hotmail', 'c 59 ', 1, '2025-06-18 17:52:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas_prestamo`
--

CREATE TABLE `cuotas_prestamo` (
  `id` int NOT NULL,
  `id_prestamo` int DEFAULT NULL,
  `numero_cuota` int DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `estado` enum('Pendiente','Pagado') COLLATE utf8mb4_general_ci DEFAULT 'Pendiente',
  `fecha_pagado` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuotas_prestamo`
--

INSERT INTO `cuotas_prestamo` (`id`, `id_prestamo`, `numero_cuota`, `fecha_pago`, `monto`, `estado`, `fecha_pagado`) VALUES
(4, 5, 1, '2025-05-29', '56.47', 'Pagado', '2025-05-31'),
(5, 5, 2, '2025-06-05', '56.47', 'Pagado', '2025-05-31'),
(6, 5, 3, '2025-06-12', '56.47', 'Pagado', '2025-05-31'),
(7, 5, 4, '2025-06-19', '56.47', 'Pagado', '2025-05-31'),
(8, 5, 5, '2025-06-26', '56.47', 'Pagado', '2025-05-31'),
(9, 5, 6, '2025-07-03', '56.47', 'Pagado', '2025-06-03'),
(10, 5, 7, '2025-07-10', '56.47', 'Pendiente', NULL),
(11, 5, 8, '2025-07-17', '56.47', 'Pendiente', NULL),
(12, 5, 9, '2025-07-24', '56.47', 'Pendiente', NULL),
(13, 4, 1, '2025-05-29', '282.45', 'Pagado', '2025-05-31'),
(14, 4, 2, '2025-06-29', '282.45', 'Pagado', '2025-05-31'),
(15, 4, 3, '2025-07-29', '282.45', 'Pagado', '2025-05-31'),
(16, 1, 1, '2025-05-29', '176.53', 'Pagado', '2025-06-23'),
(17, 1, 2, '2025-06-29', '176.53', 'Pendiente', NULL),
(18, 1, 3, '2025-07-29', '176.53', 'Pendiente', NULL),
(19, 7, 1, '2025-06-13', '429.00', 'Pagado', '2025-06-04'),
(20, 7, 2, '2025-06-28', '429.00', 'Pagado', '2025-06-05'),
(21, 7, 3, '2025-07-13', '429.00', 'Pendiente', NULL),
(22, 7, 4, '2025-07-28', '429.00', 'Pendiente', NULL),
(23, 7, 5, '2025-08-12', '429.00', 'Pendiente', NULL),
(24, 7, 6, '2025-08-27', '429.00', 'Pendiente', NULL),
(25, 8, 1, '2025-06-23', '566.51', 'Pagado', '2025-06-23'),
(26, 8, 2, '2025-06-30', '566.51', 'Pendiente', NULL),
(27, 8, 3, '2025-07-07', '566.51', 'Pendiente', NULL),
(28, 8, 4, '2025-07-14', '566.51', 'Pendiente', NULL),
(29, 8, 5, '2025-07-21', '566.51', 'Pendiente', NULL),
(30, 8, 6, '2025-07-28', '566.51', 'Pendiente', NULL),
(31, 8, 7, '2025-08-04', '566.51', 'Pendiente', NULL),
(32, 8, 8, '2025-08-11', '566.51', 'Pendiente', NULL),
(33, 8, 9, '2025-08-18', '566.51', 'Pendiente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `id` int NOT NULL,
  `empresa` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `cp` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `calles` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `imagenEmpresa` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `representante` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`id`, `empresa`, `telefono`, `cp`, `calles`, `direccion`, `imagenEmpresa`, `representante`) VALUES
(1, 'SOFTCODEPM', '+52 9995585503', '97600', ' 27 x 14 y 16', 'Dzilam Gonzalez, Yucatan, Mexico', '../images/imagenEmpresas/blacks.png', 'Ernesto Cordoba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_prestamo`
--

CREATE TABLE `detalle_prestamo` (
  `id` int NOT NULL,
  `id_prestamo` int DEFAULT NULL,
  `total_pagar` decimal(10,2) DEFAULT NULL,
  `num_cuotas` int DEFAULT NULL,
  `monto_cuota` decimal(10,2) DEFAULT NULL,
  `frecuencia_pago` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `multa_mora` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_prestamo`
--

INSERT INTO `detalle_prestamo` (`id`, `id_prestamo`, `total_pagar`, `num_cuotas`, `monto_cuota`, `frecuencia_pago`, `multa_mora`) VALUES
(1, 1, '529.59', 3, '176.53', 'mensual', '50.00'),
(2, 2, '1059.18', 3, '353.06', 'mensual', '100.00'),
(4, 4, '847.34', 3, '282.45', 'mensual', '80.00'),
(5, 5, '508.22', 9, '56.47', 'semanal', '50.00'),
(6, 6, '741.42', 3, '247.14', 'mensual', '75.00'),
(7, 7, '2573.97', 6, '429.00', 'quincenal', '87.50'),
(8, 8, '5098.63', 9, '566.51', 'semanal', '250.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_prestamo`
--

CREATE TABLE `estado_prestamo` (
  `id` int NOT NULL,
  `statusPrest` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_prestamo`
--

INSERT INTO `estado_prestamo` (`id`, `statusPrest`) VALUES
(1, 'Autorizado'),
(2, 'Revision'),
(3, 'Pendiente'),
(4, 'Cancelado'),
(5, 'Pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_registros`
--

CREATE TABLE `estado_registros` (
  `id` int NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_registros`
--

INSERT INTO `estado_registros` (`id`, `estado`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frecuencia_pago`
--

CREATE TABLE `frecuencia_pago` (
  `id` int NOT NULL,
  `frecuencia` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `frecuencia_pago`
--

INSERT INTO `frecuencia_pago` (`id`, `frecuencia`) VALUES
(1, 'Diaria'),
(2, 'Semanal'),
(3, 'Quincenal'),
(4, 'Mensual'),
(5, 'Bimestral'),
(6, 'Trimestral'),
(7, 'Unico Pago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int NOT NULL,
  `folioPrest` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_cliente` int NOT NULL,
  `id_aval` int NOT NULL,
  `id_tp` int NOT NULL,
  `monto_prestado` decimal(10,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `id_estp` int NOT NULL,
  `fechaRegistro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `folioPrest`, `id_cliente`, `id_aval`, `id_tp`, `monto_prestado`, `fecha_inicio`, `fecha_vencimiento`, `id_estp`, `fechaRegistro`) VALUES
(1, 'PRE-2025-000001', 2, 1, 6, '500.00', '2025-05-29', '2025-08-27', 1, '2025-05-29 18:58:02'),
(2, 'PRE-2025-000002', 3, 2, 6, '1000.00', '2025-05-29', '2025-08-27', 2, '2025-05-29 19:01:50'),
(4, 'PRE-2025-000004', 1, 3, 6, '800.00', '2025-05-29', '2025-08-27', 5, '2025-05-29 19:07:39'),
(5, 'PRE-2025-000005', 2, 2, 1, '500.00', '2025-05-29', '2025-08-27', 1, '2025-05-29 19:14:01'),
(6, 'PRE-2025-000006', 4, 2, 6, '700.00', '2025-06-13', '2025-09-11', 3, '2025-06-03 15:44:12'),
(7, 'PRE-2025-000007', 12, 6, 7, '2500.00', '2025-06-13', '2025-09-11', 1, '2025-06-04 18:01:26'),
(8, 'PRE-2025-000008', 12, 1, 10, '5000.00', '2025-06-23', '2025-08-22', 1, '2025-06-23 11:40:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `rol` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Usuario'),
(3, 'SuperAdministrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_prestamo`
--

CREATE TABLE `tipo_prestamo` (
  `id` int NOT NULL,
  `nombre_tipo` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(350) COLLATE utf8mb4_general_ci NOT NULL,
  `tasa_interes` decimal(5,2) NOT NULL,
  `plazo_dias` int NOT NULL,
  `id_frp` int NOT NULL,
  `multa_mora` decimal(5,2) NOT NULL,
  `monto_maximo` decimal(10,2) NOT NULL,
  `fechaRegistro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_prestamo`
--

INSERT INTO `tipo_prestamo` (`id`, `nombre_tipo`, `descripcion`, `tasa_interes`, `plazo_dias`, `id_frp`, `multa_mora`, `monto_maximo`, `fechaRegistro`) VALUES
(1, 'Prestamo Emprendedor', 'Este es un prestamo para emprendedores que inician un negocio', '10.00', 60, 2, '10.00', '5000.00', '2025-05-29 15:35:53'),
(2, 'Prestamo Personal', 'Este es un prestamo personal', '10.00', 30, 1, '50.00', '5000.00', '2025-05-29 15:38:38'),
(3, 'Prestamo Familiar', 'Este es un prestamo familiar', '25.00', 90, 6, '10.00', '1000.00', '2025-05-29 15:57:30'),
(5, 'Prestamo Ejemplo', 'Préstamo de ejemplo', '10.00', 30, 4, '5.00', '2000.00', '2025-05-29 17:28:15'),
(6, 'Préstamo de prueba mensual', 'xxxxx', '24.00', 90, 4, '10.00', '2000.00', '2025-05-29 17:45:32'),
(7, 'Préstamo Personal Básico', 'Prestamo basico Ejemplo', '12.00', 90, 3, '3.50', '15000.00', '2025-06-04 17:58:43'),
(8, 'Plan Auto Express', 'Prestamo de ejemplo 2', '8.50', 365, 4, '2.00', '120000.00', '2025-06-04 17:59:24'),
(9, 'Crédito Exprés 24h', 'Prestamo ejemplo 3\r\n', '18.00', 15, 2, '6.00', '3000.00', '2025-06-04 17:59:53'),
(10, 'Credito de 13 semanas', 'Credito de prueba', '12.00', 60, 2, '5.00', '10000.00', '2025-06-23 11:38:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `id_rol` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `imagenPerfil` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `correo`, `password`, `id_rol`, `imagenPerfil`) VALUES
(5, 'Administrador', 'administrador@gmail.com ', '$2y$05$rSGStdVtYXAeIMxNwVR1suYBn4LT7zwImjLKvEMTT7Rxx1kKlCA8W', 1, '../images/perfiles/wordpress.png'),
(43, 'Emmanuel', 'emma@gmail.com', '$2y$05$fBkfqYsjfHNmPtbKSgbrlOMIf8ufB7LZsTBT.r3XK/RYoW4Zw3Z52', 1, ''),
(47, 'Admin', 'admin@gmail.com', '$2y$10$9Tpf3Ryf3PoCvIEEFMSJVOBiqWr97.artVxPdlLjbP4oHns6gto1C', 3, '../images/perfiles/blacks.png'),
(48, 'Example', 'example@gmail.com', '$2y$05$EmSKCCxeBP0APf3jMGEsf.ONQ3iDMj7k6jdmI17.69yuCloxHyUIO', 2, ''),
(50, 'Erce Segura', 'erce@gmai.com', '$2y$10$opJvjtNPZVIk7S1m1Z7.uOyvviU2oOYouAnapdwrN77dwiUD7hPTC', 1, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `avales`
--
ALTER TABLE `avales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuotas_prestamo`
--
ALTER TABLE `cuotas_prestamo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_prestamo`
--
ALTER TABLE `estado_prestamo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_registros`
--
ALTER TABLE `estado_registros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `frecuencia_pago`
--
ALTER TABLE `frecuencia_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_prestamo`
--
ALTER TABLE `tipo_prestamo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `avales`
--
ALTER TABLE `avales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `cuotas_prestamo`
--
ALTER TABLE `cuotas_prestamo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `datos`
--
ALTER TABLE `datos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estado_prestamo`
--
ALTER TABLE `estado_prestamo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estado_registros`
--
ALTER TABLE `estado_registros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `frecuencia_pago`
--
ALTER TABLE `frecuencia_pago`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_prestamo`
--
ALTER TABLE `tipo_prestamo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
