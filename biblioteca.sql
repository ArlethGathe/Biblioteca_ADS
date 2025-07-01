-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2025 a las 09:02:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartados`
--

CREATE TABLE `apartados` (
  `id` int(10) UNSIGNED NOT NULL,
  `libro_id` int(10) UNSIGNED NOT NULL,
  `lector_id` varchar(10) NOT NULL,
  `fecha_apartado` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('apartado','cancelado') NOT NULL DEFAULT 'apartado',
  `usuario_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificaciones`
--

CREATE TABLE `clasificaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clasificaciones`
--

INSERT INTO `clasificaciones` (`id`, `nombre`) VALUES
(1, 'Literatura juvenil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`) VALUES
(1, 'Autobiografía'),
(2, 'Cuento'),
(3, 'Novela'),
(4, 'Poesía'),
(5, 'Comedia'),
(6, 'Ensayo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `editorial` varchar(255) NOT NULL,
  `edicion` varchar(50) DEFAULT NULL,
  `genero_id` int(10) UNSIGNED DEFAULT NULL,
  `clasificacion_id` int(10) UNSIGNED DEFAULT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('disponible','prestado','reservado','apartado') NOT NULL DEFAULT 'disponible',
  `portada` varchar(255) DEFAULT NULL,
  `fecha_apartado` datetime DEFAULT NULL,
  `creado_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(10) UNSIGNED NOT NULL,
  `libro_id` int(10) UNSIGNED NOT NULL,
  `lector_id` varchar(10) NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `renovado` tinyint(1) NOT NULL DEFAULT 0,
  `estado` enum('pendiente','activo','finalizado','renovado') NOT NULL DEFAULT 'pendiente',
  `usuario_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuarios` varchar(10) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `rol` enum('bibliotecario','lector','administrador') NOT NULL DEFAULT 'lector',
  `creado_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `usuario`, `correo`, `clave`, `fecha_nacimiento`, `rol`, `creado_at`) VALUES
('A203512', 'Arleth', 'arleth@gmail.com', 'arleth123', NULL, 'lector', '2025-06-30 00:14:46'),
('A273297', 'Yunis Alberto Flores Sosa ', 'yunis_alb@gmail.com', 'yunis1234', NULL, 'lector', '2025-06-30 07:11:22'),
('A953950', 'ALBERTO ', 'yunisALBERTO@gmail.com', '12345678', NULL, 'lector', '2025-06-30 05:25:11'),
('B0028', 'Carlos', 'jkhyter@gmail.com', '456789123', '1999-09-15', 'bibliotecario', '2025-06-30 07:45:14'),
('B2611', 'Fernando', 'fernando@gmail.com', 'fer123', NULL, 'bibliotecario', '2025-06-30 00:14:46'),
('C10', 'Elizabeth', 'elizabeth@gmail.com', 'elizabeth123', NULL, 'administrador', '2025-06-30 00:14:46');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartados`
--
ALTER TABLE `apartados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `lector_id` (`lector_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `clasificaciones`
--
ALTER TABLE `clasificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genero_id` (`genero_id`),
  ADD KEY `clasificacion_id` (`clasificacion_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `lector_id` (`lector_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuarios`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apartados`
--
ALTER TABLE `apartados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clasificaciones`
--
ALTER TABLE `clasificaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apartados`
--
ALTER TABLE `apartados`
  ADD CONSTRAINT `apartados_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `apartados_ibfk_2` FOREIGN KEY (`lector_id`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `apartados_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `libros_ibfk_2` FOREIGN KEY (`clasificacion_id`) REFERENCES `clasificaciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`lector_id`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
