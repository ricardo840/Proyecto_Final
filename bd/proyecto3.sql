-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-08-2025 a las 02:03:17
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
-- Base de datos: `proyecto3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ape_pa` varchar(150) NOT NULL,
  `ape_ma` varchar(150) NOT NULL,
  `genero` varchar(1) NOT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `nombre`, `ape_pa`, `ape_ma`, `genero`, `activo`) VALUES
(1, 'Ricardo', 'Bravo', 'Lino', 'M', 0),
(2, 'Julio Césarr', 'Cruz', 'García', 'M', 0),
(3, 'Víctor Alexis', 'De Anda', 'Cantero', 'M', 0),
(4, 'Sergio Emilio', 'Elizondo', 'Salman', 'M', 0),
(5, 'Ana Paola', 'Escobedo', 'Colunga', 'F', 0),
(6, 'Juan Ramón', 'Garza', 'Garza', 'M', 0),
(7, 'Antonio de Jesús', 'Gaytán', 'Rodríguez', 'M', 0),
(8, 'Daniela', 'Gerónimo', 'Juárez', 'F', 0),
(9, 'Axel Joshep', 'Ibarra', 'Grimaldo', 'M', 0),
(10, 'Álvaro Antonio', 'López', 'García', 'M', 0),
(11, 'Xiomara Guadalupe', 'Mallozzi', 'Alvarado', 'F', 0),
(12, 'Erick Iván', 'Ponce', 'Hernández', 'M', 0),
(13, 'Martín Roel', 'Rivera', 'Sánchez', 'M', 0),
(14, 'Pedro Samuel', 'Rodríguez', 'Caudillo', 'M', 0),
(15, 'Luis Brayan', 'Sandoval', 'García', 'M', 0),
(16, 'José Isaac', 'Santiago', 'Ojeda', 'M', 0),
(17, 'Rodrigo', 'Silva', 'Flores', 'M', 1),
(18, 'Omar', 'Villareal', 'Castro', 'M', 0),
(19, 'Raul ', 'Chavez', 'De Los Santos', 'M', 1),
(25, 'wewe', 'rtoo', 'rt', 'M', 1),
(26, 'si', 'si', 'si', 'M', 1),
(27, 'si', 'si', 'si', 'M', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `id_asignacion` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_maestros` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `M` int(11) NOT NULL,
  `F` int(11) NOT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion`
--

INSERT INTO `asignacion` (`id_asignacion`, `id_grupo`, `id_maestros`, `id_materia`, `fecha`, `hora`, `id_alumno`, `M`, `F`, `activo`) VALUES
(1, 2, 10, 40, '2025-07-06', '14:40:00', 5, 0, 1, 0),
(1, 2, 10, 40, '2025-07-06', '14:40:00', 7, 1, 0, 0),
(1, 2, 10, 40, '2025-07-06', '14:40:00', 8, 0, 1, 0),
(1, 2, 10, 40, '2025-07-06', '14:40:00', 9, 1, 0, 0),
(1, 2, 10, 40, '2025-07-06', '14:40:00', 16, 1, 0, 0),
(2, 2, 10, 40, '2025-07-03', '13:25:00', 5, 0, 1, 1),
(2, 2, 10, 40, '2025-07-03', '13:25:00', 7, 1, 0, 1),
(2, 2, 10, 40, '2025-07-03', '13:25:00', 9, 1, 0, 1),
(2, 2, 10, 40, '2025-07-03', '13:25:00', 10, 1, 0, 1),
(3, 7, 18, 5, '2025-06-30', '10:55:00', 1, 1, 0, 1),
(3, 7, 18, 5, '2025-06-30', '10:55:00', 3, 1, 0, 1),
(3, 7, 18, 5, '2025-06-30', '10:55:00', 17, 1, 0, 1),
(3, 7, 18, 5, '2025-06-30', '10:55:00', 18, 1, 0, 1),
(4, 5, 30, 51, '2025-07-15', '10:55:00', 1, 1, 0, 1),
(4, 5, 30, 51, '2025-07-15', '10:55:00', 5, 0, 1, 1),
(4, 5, 30, 51, '2025-07-15', '10:55:00', 7, 1, 0, 1),
(4, 5, 30, 51, '2025-07-15', '10:55:00', 8, 0, 1, 1),
(4, 5, 30, 51, '2025-07-15', '10:55:00', 16, 1, 0, 1),
(5, 9, 22, 64, '2025-08-06', '08:00:00', 6, 1, 0, 0),
(5, 9, 22, 64, '2025-08-06', '08:00:00', 8, 0, 1, 0),
(5, 9, 22, 64, '2025-08-06', '08:00:00', 10, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id_carrera`, `nombre`, `activo`) VALUES
(1, 'Ingeniería Industrial', 0),
(2, 'Ingeniería en Tecnologías de la Informaciónnnnn', 0),
(3, 'Ingeniería en Tecnologías de la Información e Innovación Digital', 0),
(4, 'Licenciatura en Administración y Gestión Empresarial', 0),
(5, 'Licenciatura en Administración', 0),
(20, 'jhgkjhgkkkk', 1),
(21, 'si', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `grupo` varchar(100) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `grupo`, `carrera`, `activo`) VALUES
(1, 'ITI 22', 2, 0),
(2, 'ITI 23', 2, 0),
(3, 'ITIID 24', 3, 0),
(4, 'II 22', 1, 1),
(5, 'II 23', 1, 0),
(6, 'II 24 01', 1, 0),
(7, 'II 24 02', 1, 0),
(8, 'LAGE 22 01', 4, 0),
(9, 'LAGE 22 02', 4, 0),
(10, 'LAGE 23', 4, 0),
(11, 'LA 24 01', 5, 0),
(12, 'LA 24 02', 5, 0),
(18, 'safafasdad', 1, 1),
(19, 'itii60', 5, 1),
(20, 'jhjkyuhi', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestros`
--

CREATE TABLE `maestros` (
  `id_maestros` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestros`
--

INSERT INTO `maestros` (`id_maestros`, `nombre`, `activo`) VALUES
(1, 'DCE. Heriberto Rene Saldaña Saldaña', 0),
(2, 'ING. Miguel Ángel Marmolejo Salinas', 0),
(3, 'MDISW. Josué Cristopher González Garza', 0),
(4, 'ME. Héctor Daniel Galindo Montemayor', 0),
(5, 'MTRO. Francisco Javier Ortega de León                              ', 0),
(6, 'ING. Angelica Vanessa Carlos Martínez', 0),
(7, 'ME. Juan Pedro Monsiváis Diaz', 0),
(8, 'ING. Samantha Gallegos Soto', 0),
(9, 'MCA. Luis Gustavo Rocha Ríos', 0),
(10, 'ING. Amanda Torres Baena', 0),
(11, 'LIC. Valeria Lizeth Pérez Espino', 0),
(12, 'LIC. Fabiola Guadalupe Barrera Barrera', 0),
(13, 'MCAEI. Carlos Enrique Aguirre Llanes', 0),
(14, 'LIC. Edgar Alan García Sánchez', 0),
(15, 'LIC. Francisco Salohi Cobaxin Camacho', 0),
(16, 'ING. Gilberto Anuar García García', 0),
(17, 'LIC. Alayssa Marisol Rodriguéz Celedón', 0),
(18, 'CP. Antonio Ismael Vázquez Salinas', 0),
(19, 'ME. Claudia Teresa Reséndez Benavidez', 0),
(20, 'LIC. Francisco Javier Soto Castillo', 0),
(21, 'LIC. Indira Idolina González Silva', 0),
(22, 'José Ángel Hernánde Partida', 0),
(23, 'LIC. José Oscar Morales Sáenz', 0),
(24, 'MTRA. Kim Karem Argüello Loyda', 0),
(25, 'ING. Kristian Carlo Cano Alvarado', 0),
(26, 'MA. Lidio Francisco Martínez', 0),
(27, 'LIC. Linda Nancy Leal García', 0),
(28, 'DCPM. Manuel De Jesús Barrena Rodríguez', 0),
(29, 'ING. Raúl Daniel González Iracheta', 0),
(30, 'Rocío Mayela Ramírez Cortez', 0),
(31, 'LIC. San Juanita Marylet Vázquez Morales', 0),
(32, 'Sergio Cavazos Gonzalez', 0),
(33, 'Stephany Janetzly García García', 0),
(34, 'Yailin Miliany Lozano Olivares', 0),
(35, 'MTRA. Zinthia Alejandra Ayala Melchor', 0),
(37, 'Martha Idalia García García', 0),
(48, 'k.ilygliygkkj', 1),
(49, 'prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_materia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id_materia`, `nombre`, `activo`) VALUES
(1, 'ADMINISTRACIÓN DE LA CALIDAD TOTAL', 1),
(2, 'ADMINISTRACIÓN DE LA PRODUCCIÓN', 0),
(5, 'CONTROL DE CALIDAD', 0),
(7, 'ESTUDIO DEL TRABAJO', 0),
(9, 'FUNDAMENTOS DE INGENIERÍA ELECTRÓNICA', 0),
(10, 'HABILIDADES ORGANIZACIONALES', 0),
(11, 'INGENIERÍA DE PLANTA', 0),
(12, 'INGLÉS III', 0),
(13, 'INGLÉS V', 0),
(14, 'INGLÉS VIII', 0),
(15, 'INVESTIGACIÓN DE OPERACIONES', 0),
(16, 'LOGÍSTICA', 0),
(17, 'OPTATIVA 1: ADMINISTRACIÓN DEL MANTENIMIENTO', 0),
(18, 'OPTATIVA 2: ADMINISTRACIÓN DE LA CADENA DE SUMINISTROS', 0),
(19, 'PROCESOS DE FABRICACIÓN', 0),
(20, 'PROYECTO INTEGRADOR I', 0),
(21, 'SIMULACIÓN DE SISTEMAS PRODUCTIVOS', 0),
(22, 'ADMINISTRACIÓN DE BASES DE DATOS', 0),
(23, 'DESARROLLO DE NEGOCIOS PARA TECNOLOGÍAS DE INFORMACIÓN', 0),
(25, 'HABILIDADES GERENCIALES', 0),
(26, 'INGLÉS IX', 0),
(28, 'INTELIGENCIA DE NEGOCIOS', 0),
(29, 'INTERCONEXIÓN DE REDES', 0),
(30, 'MATEMÁTICAS PARA INGENIERÍA II', 0),
(31, 'PROGRAMACIÓN MÓVIL', 0),
(33, 'SEGURIDAD INFORMÁTICA', 0),
(34, 'SISTEMAS EMBEBIDOS', 0),
(35, 'SISTEMAS OPERATIVOS', 0),
(36, 'BASES DE DATOS', 0),
(37, 'CÁLCULO INTEGRAL', 0),
(38, 'DESARROLLO DEL PENSAMIENTO Y TOMA DE DECISIONES', 0),
(40, 'PROGRAMACIÓN ORIENTADA A OBJETOS', 0),
(42, 'TÓPICOS DE CALIDAD PARA EL DISEÑO DE SOFTWARE', 0),
(43, 'ANÁLISIS FINANCIERO', 0),
(45, 'FUNDAMENTOS DE CALIDAD', 0),
(46, 'FUNDAMENTOS DE MERCADOTECNIA', 0),
(48, 'MACROECONOMÍA', 0),
(50, 'ADMINISTRACIÓN DE LA CALIDAD', 0),
(51, 'ADMINISTRACIÓN DE REDES EMPRESARIALES', 0),
(52, 'ADMINISTRACIÓN DE SUELDOS Y SALARIOS', 0),
(53, 'ADMINISTRACIÓN FINANCIERA', 0),
(54, 'COMERCIALIZACIÓN INTERNACIONAL', 0),
(55, 'CONSULTORÍA', 0),
(56, 'ECONOMETRÍA', 0),
(57, 'EXPRESIÓN ORAL Y ESCRITA II', 0),
(58, 'GESTIÓN DE MARCA', 0),
(59, 'GESTIÓN Y EVALUACIÓN DE PROYECTOS', 0),
(61, 'INGLÉS VI', 0),
(62, 'LIDERAZGO DE EQUIPOS DE ALTO DESEMPEÑO', 0),
(63, 'MERCADOTECNIA ESTRATÉGICA', 0),
(64, 'TUTORÍA', 0),
(70, 'utdryu70', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias_carrera`
--

CREATE TABLE `materias_carrera` (
  `id_materia_carrera` int(11) NOT NULL,
  `id_materia` int(11) DEFAULT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias_carrera`
--

INSERT INTO `materias_carrera` (`id_materia_carrera`, `id_materia`, `id_carrera`, `activo`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 0),
(3, 43, 1, 0),
(4, 5, 1, 0),
(5, 37, 1, 0),
(6, 38, 1, 0),
(7, 7, 1, 0),
(9, 9, 1, 0),
(10, 10, 1, 0),
(11, 11, 1, 0),
(12, 12, 1, 0),
(13, 13, 1, 0),
(14, 14, 1, 0),
(15, 15, 1, 0),
(16, 16, 1, 0),
(17, 17, 1, 0),
(18, 18, 1, 0),
(19, 19, 1, 0),
(20, 20, 1, 0),
(21, 21, 1, 0),
(22, 22, 2, 0),
(23, 23, 2, 0),
(24, 57, 2, 0),
(25, 25, 2, 0),
(26, 26, 2, 0),
(27, 61, 2, 0),
(28, 28, 2, 0),
(29, 29, 2, 0),
(30, 30, 2, 0),
(31, 31, 2, 0),
(32, 40, 2, 0),
(33, 33, 2, 0),
(34, 34, 2, 0),
(35, 35, 2, 0),
(36, 36, 3, 0),
(37, 37, 3, 0),
(38, 38, 3, 0),
(39, 12, 3, 0),
(40, 40, 3, 0),
(41, 38, 3, 0),
(42, 42, 3, 0),
(43, 43, 5, 0),
(44, 38, 5, 0),
(45, 45, 5, 0),
(46, 46, 5, 0),
(48, 48, 5, 0),
(50, 50, 4, 0),
(51, 51, 4, 0),
(52, 52, 4, 0),
(53, 53, 4, 0),
(54, 54, 4, 0),
(55, 55, 4, 0),
(56, 56, 4, 0),
(57, 57, 4, 0),
(58, 58, 4, 0),
(59, 59, 4, 0),
(60, 26, 4, 0),
(61, 61, 4, 0),
(62, 62, 4, 0),
(63, 63, 4, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias_grupo`
--

CREATE TABLE `materias_grupo` (
  `id_materia_grupo` int(11) NOT NULL,
  `id_materia` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_maestro` int(11) DEFAULT NULL,
  `activo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias_grupo`
--

INSERT INTO `materias_grupo` (`id_materia_grupo`, `id_materia`, `id_grupo`, `id_maestro`, `activo`) VALUES
(1, 28, 4, 1, 0),
(2, 33, 1, 4, 0),
(3, 23, 1, 7, 0),
(4, 31, 1, 3, 0),
(6, 26, 1, 33, 0),
(7, 34, 1, 29, 0),
(8, 64, 1, 7, 0),
(9, 29, 2, 4, 0),
(10, 22, 2, 8, 0),
(11, 25, 2, 11, 0),
(12, 30, 2, 9, 0),
(14, 35, 2, 7, 0),
(16, 64, 2, 11, 0),
(17, 36, 3, 2, 0),
(18, 37, 3, 16, 0),
(19, 38, 3, 21, 0),
(20, 42, 3, 7, 0),
(22, 40, 3, 8, 0),
(24, 64, 3, 2, 0),
(25, 1, 4, 26, 0),
(26, 14, 4, 12, 0),
(27, 16, 4, 35, 0),
(28, 17, 4, 7, 0),
(29, 18, 4, 31, 0),
(31, 21, 4, 28, 0),
(32, 64, 4, 28, 0),
(33, 10, 5, 11, 0),
(34, 9, 5, 29, 0),
(35, 15, 5, 9, 0),
(36, 2, 5, 20, 0),
(37, 7, 5, 32, 0),
(38, 13, 5, 5, 0),
(39, 11, 5, 35, 0),
(40, 64, 5, 5, 0),
(41, 5, 6, 9, 0),
(42, 20, 6, 25, 0),
(43, 19, 6, 35, 0),
(45, 7, 6, 28, 0),
(47, 12, 6, 23, 0),
(48, 64, 6, 37, 0),
(49, 19, 7, 35, 0),
(50, 7, 7, 28, 0),
(52, 20, 7, 25, 0),
(54, 5, 7, 9, 0),
(56, 64, 7, 9, 0),
(57, 55, 8, 18, 0),
(58, 58, 8, 34, 0),
(59, 51, 8, 26, 0),
(61, 57, 8, 6, 0),
(62, 54, 8, 20, 0),
(63, 59, 8, 13, 0),
(64, 64, 8, 13, 0),
(65, 58, 9, 34, 0),
(66, 55, 9, 18, 0),
(67, 59, 9, 13, 0),
(68, 54, 9, 20, 0),
(69, 51, 9, 26, 0),
(71, 57, 9, 6, 0),
(72, 64, 9, 6, 0),
(73, 52, 10, 17, 0),
(74, 63, 10, 14, 0),
(75, 56, 10, 15, 0),
(76, 50, 10, 1, 0),
(77, 53, 10, 11, 0),
(78, 61, 10, 12, 0),
(79, 62, 10, 19, 0),
(80, 64, 10, 17, 0),
(81, 43, 11, 22, 0),
(83, 45, 11, 14, 0),
(84, 46, 11, 31, 0),
(86, 48, 11, 15, 0),
(88, 64, 11, 35, 0),
(90, 48, 12, 15, 0),
(91, 45, 12, 14, 0),
(92, 43, 12, 22, 0),
(94, 46, 12, 31, 0),
(97, 57, 1, 6, 0),
(98, 40, 2, 10, 0),
(99, 61, 2, 12, 0),
(100, 12, 3, 33, 0),
(101, 20, 3, 1, 0),
(102, 43, 4, 17, 0),
(103, 12, 6, 23, 0),
(104, 20, 6, 25, 0),
(106, 12, 7, 23, 0),
(107, 38, 7, 37, 0),
(108, 37, 7, 16, 0),
(109, 26, 8, 5, 0),
(110, 26, 9, 5, 0),
(112, 7, 7, 14, 1),
(113, 2, 6, 4, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo_usuario` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `tipo_usuario`, `fecha_registro`) VALUES
(1, 'Lino', 'lino@gmail.com', '$2y$10$HlX7TA8EwlI76Zp.FC6u5.IrkhXBMyAzVMxjkPI.ZeUbVVu0czYIy', 'usuario', '2025-08-07 00:39:52'),
(2, 'Lino', 'lino1@gmail.com', '$2y$10$i2QAfUTP3UsGfdS6OLQHHu3C2IZpz4ueuIKsla1Xj8qKlDlnSgVlC', 'usuario', '2025-08-07 00:44:14'),
(3, 'ricardo', 'ricardo@gmail.com', '$2y$10$75vs21MfoJrDHZrIRoPslOF9IqHkZbZbmp7L0R9LRVIXHiBKVnM7O', 'admin', '2025-08-07 08:47:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`);

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`id_asignacion`,`id_alumno`),
  ADD UNIQUE KEY `unique_asignacion` (`id_asignacion`,`id_alumno`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_maestros` (`id_maestros`),
  ADD KEY `id_materia` (`id_materia`),
  ADD KEY `id_alumno` (`id_alumno`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id_carrera`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `carrera` (`carrera`);

--
-- Indices de la tabla `maestros`
--
ALTER TABLE `maestros`
  ADD PRIMARY KEY (`id_maestros`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materia`);

--
-- Indices de la tabla `materias_carrera`
--
ALTER TABLE `materias_carrera`
  ADD PRIMARY KEY (`id_materia_carrera`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `materias_carrera_ibfk_1` (`id_materia`);

--
-- Indices de la tabla `materias_grupo`
--
ALTER TABLE `materias_grupo`
  ADD PRIMARY KEY (`id_materia_grupo`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_maestro` (`id_maestro`),
  ADD KEY `materias_grupo_ibfk_1` (`id_materia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `maestros`
--
ALTER TABLE `maestros`
  MODIFY `id_maestros` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `materias_carrera`
--
ALTER TABLE `materias_carrera`
  MODIFY `id_materia_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `materias_grupo`
--
ALTER TABLE `materias_grupo`
  MODIFY `id_materia_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD CONSTRAINT `asignacion_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`),
  ADD CONSTRAINT `asignacion_ibfk_2` FOREIGN KEY (`id_maestros`) REFERENCES `maestros` (`id_maestros`),
  ADD CONSTRAINT `asignacion_ibfk_3` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  ADD CONSTRAINT `asignacion_ibfk_4` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`);

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`carrera`) REFERENCES `carreras` (`id_carrera`);

--
-- Filtros para la tabla `materias_carrera`
--
ALTER TABLE `materias_carrera`
  ADD CONSTRAINT `materias_carrera_ibfk_1` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE SET NULL,
  ADD CONSTRAINT `materias_carrera_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`);

--
-- Filtros para la tabla `materias_grupo`
--
ALTER TABLE `materias_grupo`
  ADD CONSTRAINT `materias_grupo_ibfk_1` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE,
  ADD CONSTRAINT `materias_grupo_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`),
  ADD CONSTRAINT `materias_grupo_ibfk_3` FOREIGN KEY (`id_maestro`) REFERENCES `maestros` (`id_maestros`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
