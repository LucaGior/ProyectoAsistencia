-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-11-2024 a las 19:12:20
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_asistencias`
--
CREATE DATABASE IF NOT EXISTS `sistema_asistencias` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci;
USE `sistema_asistencias`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apellido`, `email`, `dni`, `fecha_nac`, `telefono`) VALUES
(50, 'Valentino', 'Andrade', 'valentino.andrade@email.com', '35123456', '1999-03-12', '3512345678'),
(51, 'Lucas', 'Cedres', 'lucas.cedres@email.com', '34876543', '1998-09-07', '3512345679'),
(52, 'Facundo', 'Figun', 'facundo.figun@email.com', '40123789', '2000-11-25', '3512345680'),
(53, 'Luca', 'Giordano', 'luca.giordano@email.com', '32456789', '1997-06-02', '3512345681'),
(54, 'Bruno', 'Godoy', 'bruno.godoy@email.com', '36789123', '1999-01-18', '3512345682'),
(55, 'Agustin', 'Gomez', 'agustin.gomez@email.com', '33567890', '1996-04-30', '3512345683'),
(56, 'Brian', 'Gonzalez', 'brian.gonzalez@email.com', '35678901', '1997-12-05', '3512345684'),
(57, 'Federico', 'Guigou Scottini', 'federico.guigou@email.com', '37890123', '1998-08-15', '3512345685'),
(58, 'Luna', 'Marrano', 'luna.marrano@email.com', '38901234', '1999-03-10', '3512345686'),
(59, 'Giuliana', 'Mercado Aviles', 'giuliana.mercado@email.com', '33345678', '1995-10-22', '3512345687'),
(60, 'Lucila', 'Mercado Ruiz', 'lucila.mercado@email.com', '32567890', '1996-12-08', '3512345688'),
(61, 'Angel', 'Murillo', 'angel.murillo@email.com', '34890123', '1998-02-27', '3512345689'),
(62, 'Juan', 'Nissero', 'juan.nissero@email.com', '36123456', '1999-07-17', '3512345690'),
(63, 'Fausto', 'Parada', 'fausto.parada@email.com', '35234567', '1997-11-06', '3512345691'),
(64, 'Ignacio', 'Piter', 'ignacio.piter@email.com', '32789012', '1996-05-19', '3512345692'),
(65, 'Tomas', 'Planchon', 'tomas.planchon@email.com', '40456789', '2000-09-03', '3512345693'),
(66, 'Elisa', 'Ronconi', 'elisa.ronconi@email.com', '31678123', '1995-01-24', '3512345694'),
(67, 'Exequiel', 'Sanchez', 'exequiel.sanchez@email.com', '33234567', '1998-04-11', '3512345695'),
(68, 'Melina', 'Schimpf Baldo', 'melina.schimpf@email.com', '33789456', '1996-10-09', '3512345696'),
(69, 'Diego', 'Segovia', 'diego.segovia@email.com', '34567890', '1997-02-13', '3512345697'),
(70, 'Camila', 'Sittner', 'camila.sittner@email.com', '36456789', '1999-08-20', '3512345698'),
(71, 'Yamil', 'Villa', 'yamil.villa@email.com', '35345678', '1998-06-28', '3512345699');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int NOT NULL,
  `id_inscripcion` int DEFAULT NULL,
  `fecha` date NOT NULL,
  `estado` enum('presente','ausente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int NOT NULL,
  `id_alumno` int DEFAULT NULL,
  `id_materia` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `id_alumno`, `id_materia`) VALUES
(66, 50, 51),
(67, 51, 51),
(68, 52, 51),
(69, 53, 51),
(70, 54, 51),
(71, 55, 51),
(72, 56, 51),
(73, 57, 51),
(74, 58, 51),
(75, 59, 51),
(76, 60, 51),
(77, 61, 51),
(78, 62, 51),
(79, 63, 51),
(80, 64, 51),
(81, 65, 51),
(82, 66, 51),
(83, 67, 51),
(84, 68, 51),
(85, 69, 51),
(86, 70, 51),
(87, 71, 51);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instituciones`
--

CREATE TABLE `instituciones` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `instituciones`
--

INSERT INTO `instituciones` (`id`, `nombre`, `direccion`) VALUES
(31, 'sedes sapientiae', 'santa fe 70');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_institucion` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nombre`, `id_institucion`) VALUES
(51, 'Programacion 2', 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int NOT NULL,
  `id_inscripcion` int DEFAULT NULL,
  `nota1` decimal(5,2) DEFAULT NULL,
  `nota2` decimal(5,2) DEFAULT NULL,
  `nota3` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ram`
--

CREATE TABLE `ram` (
  `id` int NOT NULL,
  `asistencia_regular` decimal(4,2) NOT NULL,
  `asistencia_promocion` decimal(4,2) NOT NULL,
  `nota_regular` int NOT NULL,
  `nota_promocion` int NOT NULL,
  `id_institucion` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ram`
--

INSERT INTO `ram` (`id`, `asistencia_regular`, `asistencia_promocion`, `nota_regular`, `nota_promocion`, `id_institucion`) VALUES
(2, 60.00, 70.00, 6, 7, 31);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_inscripcion` (`id_inscripcion`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_alumno` (`id_alumno`,`id_materia`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `instituciones`
--
ALTER TABLE `instituciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_institucion` (`id_institucion`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_inscripcion` (`id_inscripcion`);

--
-- Indices de la tabla `ram`
--
ALTER TABLE `ram`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_institucion_ram` (`id_institucion`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `instituciones`
--
ALTER TABLE `instituciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `ram`
--
ALTER TABLE `ram`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`id_inscripcion`) REFERENCES `inscripciones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `instituciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_inscripcion`) REFERENCES `inscripciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ram`
--
ALTER TABLE `ram`
  ADD CONSTRAINT `fk_institucion_ram` FOREIGN KEY (`id_institucion`) REFERENCES `instituciones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
