-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 01-07-2025 a las 23:40:03
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
(1, 'Literatura juvenil'),
(2, 'Literatura clásica'),
(3, 'Literatura latinoamericana'),
(4, 'Bestseller'),
(5, 'Novela negra'),
(6, 'Literatura contemporánea'),
(7, 'Juvenil'),
(8, 'Drama romántico'),
(9, 'Aventuras clásicas'),
(10, 'Aventura futurística'),
(11, 'Literatura infantil'),
(12, 'Distopía'),
(13, 'Space opera'),
(14, 'Autoayuda'),
(15, 'Adultos');

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
(6, 'Ensayo'),
(7, 'Ciencia ficción'),
(8, 'Realismo mágico'),
(9, 'Romance'),
(10, 'Misterio'),
(11, 'Suspenso'),
(12, 'Terror'),
(13, 'Fantasía'),
(14, 'Desarrollo personal'),
(15, 'Biografía'),
(17, 'Espiritualidad'),
(18, 'Thriller'),
(19, 'Existencialismo');

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

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `editorial`, `edicion`, `genero_id`, `clasificacion_id`, `cantidad`, `estado`, `portada`, `fecha_apartado`, `creado_at`) VALUES
(1, 'El Diario de Ana Frank', 'Ana Frank', 'Debolsillo', '1', 1, 1, 7, 'disponible', 'anafrank.jpeg', NULL, '2025-06-29 20:23:54'),
(2, '1984', 'George Orwell', 'Debolsillo', '1', 7, 2, 5, 'disponible', '1984.jpeg', NULL, '2025-06-30 05:33:26'),
(3, 'Cien años de soledad', 'Gabriel García Marquez', 'Sudamericana', '1', 8, 3, 7, 'disponible', 'soledad.jpeg', NULL, '2025-06-30 05:36:09'),
(4, 'Orgullo y prejuicio', 'Jane Austen', 'Penguin Clásicos', '2', 9, 2, 3, 'disponible', 'orgulloyPrejuicio.jpeg', NULL, '2025-06-30 05:37:02'),
(6, 'El código Da Vinci', 'Dan Brown', 'Planeta', '1', 10, 4, 6, 'disponible', 'CodigoDaVinci.jpeg', NULL, '2025-06-30 06:14:57'),
(7, 'Los hombres que no amaban a las mujeres', 'Stieg Larsson', 'Destino', '1', 18, 5, 4, 'disponible', 'loshombres.jpeg', NULL, '2025-06-30 06:51:32'),
(10, 'La chica del tren', 'Paul Hawkins', 'Planeta', '1', 11, 4, 5, 'disponible', 'chicatren.jpeg', NULL, '2025-06-30 07:52:51'),
(11, 'It', 'Stephen King', 'Plaza & Janés', '1', 12, 6, 3, 'disponible', 'eso.jpg', NULL, '2025-06-30 07:53:41'),
(12, 'Drácula', 'Bram Stoker', 'Alianza Editorial', '2', 12, 2, 2, 'disponible', 'dracula.jpeg', NULL, '2025-06-30 07:54:33'),
(13, 'Yo antes de ti', 'Jojo Moyes', 'Suma', '1', 9, 4, 6, 'disponible', 'mebeforeyou.jpeg', NULL, '2025-06-30 07:55:17'),
(14, 'Bajo la misma estrella', 'John Green', 'Nube de tinta', '1', 9, 7, 5, 'disponible', 'estrella.jpeg', NULL, '2025-06-30 08:01:32'),
(15, 'El cuaderno de Noah', 'Nicholas Sparks', 'Roca', '1', 9, 8, 4, 'disponible', 'cuadernoNoah.jpeg', NULL, '2025-06-30 08:08:28'),
(16, 'Harry Potter y la piedra filosofal', 'J.K Rowling', 'Salamandra', '1', 13, 1, 10, 'disponible', 'harrypotter.jpeg', NULL, '2025-06-30 08:11:32'),
(17, 'El hobbit', 'J.R.R Tolkien', 'Minotauro', '1', 13, 9, 2, 'reservado', 'portadas/default.png', NULL, '2025-06-30 08:13:54'),
(18, 'Las crónicas de Narnia', 'C.S Lewis', 'Destino', '1', 7, 13, 4, 'disponible', 'narnia.jpeg', NULL, '2025-06-30 08:14:43'),
(19, 'The Fahrenheit 451', 'Ray Bradbury', 'Minotauro', '2', 7, 12, 2, 'prestado', 'fahrenheit.jpeg', NULL, '2025-06-30 08:19:47'),
(20, 'Ready Player One', 'Ernest Cline', 'Nova', '1', 7, 10, 3, 'prestado', 'readyplayer.jpeg', NULL, '2025-06-30 08:20:32'),
(21, 'Los 7 hábitos de la gente altamente efectiva', 'Stephen Covey', 'Paidós', '3', 14, 14, 4, 'prestado', 'sietehabitos.jpeg', NULL, '2025-06-30 08:21:36'),
(22, 'El poder del ahora', 'Eckhart Tolle', 'Gaia Ediciones', '1', 17, 14, 4, 'disponible', 'poderahora.jpeg', NULL, '2025-06-30 08:22:21'),
(23, 'Metamorfosis', 'Franz Kafka', 'Alianza Editorial', '1', 19, 15, 2, 'disponible', 'metamorfosis.jpeg', NULL, '2025-06-30 08:25:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multas`
--

CREATE TABLE `multas` (
  `id_multa` int(4) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `cantidad_pesos` int(3) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `multas`
--

INSERT INTO `multas` (`id_multa`, `usuario`, `titulo`, `fecha_vencimiento`, `cantidad_pesos`, `descripcion`) VALUES
(2, 'ALBERTO ', 'It', '0000-00-00', 200, 'Retraso en la entrega del libro'),
(3, 'Arleth', 'El cuaderno de Noah', '0000-00-00', 400, 'Retraso en la entrega del libro y daño');

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

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `libro_id`, `lector_id`, `fecha_solicitud`, `fecha_inicio`, `fecha_vencimiento`, `renovado`, `estado`, `usuario_id`) VALUES
(1, 19, 'Yunis Albe', '2025-07-01 04:57:15', NULL, NULL, 0, 'pendiente', NULL),
(2, 20, 'Yunis Albe', '2025-07-01 12:53:40', NULL, NULL, 0, 'pendiente', NULL),
(3, 21, 'Yunis Albe', '2025-07-01 12:53:52', NULL, NULL, 0, 'pendiente', NULL);

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
('A273297', 'Yunis Alberto Flores Sosa ', 'yunis_alb@gmail.com', 'yunis1234', '2004-10-28', 'lector', '2025-06-30 07:11:22'),
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
  ADD KEY `fk_apartados_libro` (`libro_id`),
  ADD KEY `fk_apartados_lector_` (`lector_id`);

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
  ADD KEY `fk_libros_genero` (`genero_id`),
  ADD KEY `fk_libros_clasif` (`clasificacion_id`);

--
-- Indices de la tabla `multas`
--
ALTER TABLE `multas`
  ADD PRIMARY KEY (`id_multa`),
  ADD KEY `usuario` (`usuario`,`titulo`,`fecha_vencimiento`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `multas`
--
ALTER TABLE `multas`
  MODIFY `id_multa` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
