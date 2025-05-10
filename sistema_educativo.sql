-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-04-2025 a las 22:47:35
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_educativo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int NOT NULL,
  `nombre_curso` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`) VALUES
(1, 'Matemáticas'),
(2, 'Español'),
(3, 'Religión'),
(4, 'Civica'),
(5, 'Estudios Sociales'),
(6, 'Ciencias'),
(7, 'Inglés');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `nivel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre`, `apellido`, `usuario_id`, `nivel`) VALUES
(1, 'María', 'López Cruz', 3, 'Primero'),
(2, 'Alberto', 'Torres Bonilla', 5, 'Segundo'),
(3, 'Isabella', 'Vindas Navarro', 10, 'Tercero'),
(7, 'Alejandro', 'Gomez', 11, 'Primero'),
(8, 'Mariana', 'Rodriguez', 12, 'Primero'),
(9, 'Luis', 'Fernandez', 13, 'Primero'),
(10, 'Sofia', 'Martinez', 14, 'Primero'),
(11, 'Javier', 'Lopez', 15, 'Segundo'),
(12, 'Camila', 'Torres', 16, 'Segundo'),
(13, 'Andres', 'Ramirez', 17, 'Segundo'),
(14, 'Valeria', 'Castillo', 18, 'Segundo'),
(15, 'Ricardo', 'Mendez', 19, 'Tercero'),
(16, 'Gabriela', 'Salazar', 20, 'Tercero'),
(17, 'Fernando', 'Herrera', 21, 'Tercero'),
(18, 'Natalia', 'Jimenez', 22, 'Tercero'),
(19, 'Sebastian', 'Vazquez', 23, 'Cuarto'),
(20, 'Paula', 'Rios', 24, 'Cuarto'),
(21, 'Daniel', 'Perez', 25, 'Cuarto'),
(22, 'Carolina', 'Silva', 26, 'Cuarto'),
(23, 'Mateo', 'Castro', 27, 'Cuarto'),
(24, 'Isabel', 'Moreno', 28, 'Quinto'),
(25, 'Manuel', 'Ortega', 32, 'Quinto'),
(26, 'Elena', 'Aguilar', 33, 'Quinto'),
(27, 'Andrea', 'Jimenez', 37, 'Quinto'),
(28, 'Edgar', 'Alvarez', 38, 'Quinto'),
(29, 'Leonor', 'Dobles', 39, 'Sexto'),
(30, 'Kathia', 'Alvarez', 40, 'Sexto'),
(31, 'Pedro', 'Mares', 41, 'Sexto'),
(32, 'Henry', 'Arrancel', 42, 'Sexto'),
(33, 'Astrid', 'Hernandez', 43, 'Sexto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id_nota` int NOT NULL,
  `id_estudiante` int DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_profesor` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id_nota`, `id_estudiante`, `id_curso`, `nota`, `fecha_registro`, `id_profesor`) VALUES
(5, 2, 2, 90.00, '2025-04-15 16:42:58', NULL),
(7, 1, 5, 95.00, '2025-04-18 18:29:20', NULL),
(8, 1, 6, 98.00, '2025-04-18 18:29:39', NULL),
(9, 7, 6, 80.00, '2025-04-18 19:07:11', NULL),
(10, 7, 5, 78.00, '2025-04-18 19:07:27', NULL),
(11, 8, 2, 89.00, '2025-04-18 19:07:39', NULL),
(12, 10, 7, 56.00, '2025-04-18 19:07:52', NULL),
(13, 13, 7, 98.00, '2025-04-18 19:08:48', NULL),
(14, 14, 1, 78.00, '2025-04-18 19:08:56', NULL),
(15, 14, 3, 87.00, '2025-04-18 19:09:10', NULL),
(16, 3, 6, 78.00, '2025-04-18 19:10:00', NULL),
(17, 16, 3, 78.00, '2025-04-18 19:10:09', NULL),
(18, 18, 1, 89.00, '2025-04-18 19:10:25', NULL),
(19, 18, 5, 80.00, '2025-04-18 19:10:36', NULL),
(20, 21, 4, 80.00, '2025-04-18 19:11:11', NULL),
(21, 19, 1, 78.00, '2025-04-18 19:11:21', NULL),
(22, 23, 5, 88.00, '2025-04-18 19:11:32', NULL),
(23, 20, 5, 90.00, '2025-04-18 19:11:43', NULL),
(24, 28, 4, 88.00, '2025-04-18 19:12:15', NULL),
(25, 24, 5, 89.00, '2025-04-18 19:12:24', NULL),
(26, 33, 6, 100.00, '2025-04-18 19:13:02', NULL),
(27, 32, 2, 100.00, '2025-04-18 19:13:14', NULL),
(28, 30, 5, 98.00, '2025-04-18 19:13:23', NULL),
(29, 29, 2, 100.00, '2025-04-18 19:13:33', NULL),
(30, 31, 3, 100.00, '2025-04-18 19:13:45', NULL),
(31, 7, 3, 100.00, '2025-04-19 14:00:21', NULL),
(32, 14, 6, 98.00, '2025-04-19 14:25:23', NULL),
(33, 12, 2, 90.00, '2025-04-19 14:26:03', NULL),
(35, 9, 6, 100.00, '2025-04-19 14:40:18', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesor` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `nivel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `nombre`, `apellido`, `usuario_id`, `nivel`) VALUES
(1, 'Carlos', 'Ramírez Quirós', 2, 'Primero'),
(2, 'Ignacio Alejandro ', 'Cascanate Navarro', 4, 'Segundo'),
(3, 'Karen ', 'Navarro Hidalgo', 6, 'Tercero'),
(4, 'Luis ', 'Ramírez López', 7, 'Cuarto'),
(5, 'María ', 'Mora Monge', 8, 'Quinto'),
(6, 'Emanuel', 'Alfaro Ruiz', 9, 'Sexto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('administrador','profesor','estudiante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `rol`) VALUES
(1, 'admin', 'admin123', 'administrador'),
(2, 'cramirezq', 'Profesor03', 'profesor'),
(3, 'mlopezc', 'Maria2025', 'estudiante'),
(4, 'icascanten17', 'Soporte2025*', 'profesor'),
(5, 'atorres', 'Atorres1', 'estudiante'),
(6, 'knavarroh', 'Profesor01', 'profesor'),
(7, 'lramirezl', 'Profesor02', 'profesor'),
(8, 'mmoram', 'Profesor04', 'profesor'),
(9, 'ealfaror', 'Profesor05', 'profesor'),
(10, 'ivindasn', 'Isabella2025*', 'estudiante'),
(11, 'alegomez', 'ale123', 'estudiante'),
(12, 'marirodri', 'mari123', 'estudiante'),
(13, 'luisfer', 'luis123', 'estudiante'),
(14, 'sofimarti', 'sofi123', 'estudiante'),
(15, 'javilope', 'javi1238', 'estudiante'),
(16, 'camitorre', 'cami289137', 'estudiante'),
(17, 'andreram', 'andre721839', 'estudiante'),
(18, 'valecasti', 'vale189k', 'estudiante'),
(19, 'ricarmende', 'ricado478', 'estudiante'),
(20, 'gabisala', 'salazar8923', 'estudiante'),
(21, 'ferherrara', 'fernenh9383', 'estudiante'),
(22, 'natijime', 'nati2137', 'estudiante'),
(23, 'sebasvazque', 'sebas213u', 'estudiante'),
(24, 'paulario', 'rio2873', 'estudiante'),
(25, 'danipe', 'perez7388', 'estudiante'),
(26, 'caros', 'silva78236', 'estudiante'),
(27, 'metecastro', 'mcastro1974', 'estudiante'),
(28, 'isamoreno', 'isa1973', 'estudiante'),
(32, 'manuort', 'ortegamanu', 'estudiante'),
(33, 'elenaAgui', 'Agui81238', 'estudiante'),
(37, 'andreji', 'jimeandre', 'estudiante'),
(38, 'Edgara', 'Alvarez123', 'estudiante'),
(39, 'LeonorD', 'Dobles1234', 'estudiante'),
(40, 'Kathia', 'KathiaEverez1', 'estudiante'),
(41, 'PedroM', 'PeMares', 'estudiante'),
(42, 'HenryArran', 'HenryArrancel', 'estudiante'),
(43, 'Astrid', 'AstriHernandez', 'estudiante');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_profesor_2` (`id_profesor`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id_nota` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_4` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

--
-- Filtros para la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD CONSTRAINT `profesores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
