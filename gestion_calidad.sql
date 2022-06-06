-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-03-2019 a las 17:11:06
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_calidad`
--
CREATE DATABASE 'gestion_calidad';

USE DATABASE 'gestion_calidad';
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_documentos` int(11) NOT NULL,
  `nombre_documento` varchar(100) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `baja` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id_documentos`, `nombre_documento`, `fecha_inicio`, `fecha_fin`, `baja`) VALUES
(1, 'Tanto Per Cento', '2019-07-01', '2019-07-31', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_lineas`
--

CREATE TABLE `documentos_lineas` (
  `id_documentos_lineas` int(11) NOT NULL AUTO_INCREMENT,
  `id_documentos` int(11) NOT NULL,
  `titulo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `id_tipo` int(11) NOT NULL,
    PRIMARY KEY (id_documentos_lineas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `documentos_lineas`
--

INSERT INTO `documentos_lineas` (`id_documentos_lineas`, `id_documentos`, `titulo`, `descripcion`, `id_tipo`) VALUES
(1, 1, 'Numero de unidades', 'Indique el numero de unidades, temas o bloques impartidos', 3);

INSERT INTO `documentos_lineas` (`id_documentos_lineas`, `id_documentos`, `titulo`, `descripcion`, `id_tipo`) VALUES
(2, 1, 'Numero de horas de clases impartidas', 'Numero de las horas de las clases que usted ha impartido', 3);

INSERT INTO `documentos_lineas` (`id_documentos_lineas`, `id_documentos`, `titulo`, `descripcion`, `id_tipo`) VALUES
(3, 1, 'Asistencia de los alumnos', 'Indique la asistencia de los alumnos durante el curso', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id_personal` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `cookie` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id_personal`, `nombre`, `apellidos`, `email`, `password`, `cookie`) VALUES
(1, 'Admin', '', 'admin@iesjuanbosco.com', '1234', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Profesor'),
(3, 'Alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_rol`
--

CREATE TABLE `personal_rol` (
  `id` int(11) NOT NULL,
  `id_personal` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personal_rol`
--

INSERT INTO `personal_rol` (`id`, `id_personal`, `id_rol`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id_respuesta` int(11) NOT NULL AUTO_INCREMENT,
  `id_documentos_lineas` int(11) DEFAULT NULL,
  `id_personal` int(11) DEFAULT NULL,
  `fecha_respuesta` date DEFAULT NULL,
  `respuestas` varchar(800) DEFAULT NULL,
   previstas int(11) DEFAULT 0,
   impartidas int(11) DEFAULT 0,
   asignatura int(11) DEFAULT 0,
   curso int(11) DEFAULT 0,
   rol int(11) DEFAULT 0
    PRIMARY KEY (`id_respuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


create table respondido (
    id_respondido int not null AUTO_INCREMENT,
    id_personal int not null,
    id_documentos int not null,
    id_rol int not null,
    id_asignatura int not null,
    id_curso int not null,
    respondido int DEFAULT 0,
    PRIMARY KEY (id_respondido),
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal),
    FOREIGN KEY (id_documentos) REFERENCES documentos(id_documentos)
);
/*    FOREIGN KEY (id_rol) REFERENCES rol(id_rol),
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)*/
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_documentos`
--

CREATE TABLE `rol_documentos` (
  `id` int(11) NOT NULL,
  `id_documentos` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol_documentos`
--

INSERT INTO `rol_documentos` (`id`, `id_documentos`, `id_rol`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_preguntas`
--

CREATE TABLE `tipos_preguntas` (
  `id_tipo` int(11) NOT NULL,
  `tipo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipos_preguntas`
--

INSERT INTO `tipos_preguntas` (`id_tipo`, `tipo`) VALUES
(1, 'texto'),
(2, 'puntuacion'),
(3, 'calculo_porcentaje');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documentos`);

--
-- Indices de la tabla `documentos_lineas`
--
ALTER TABLE `documentos_lineas`
  ADD PRIMARY KEY (`id_documentos_lineas`),
  ADD KEY `fk_Documentos_Lineas_Documentos1_idx` (`id_documentos`),
  ADD KEY `fk_Documentos_Lineas_Tipos_Preguntas1_idx` (`id_tipo`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indices de la tabla `personal_rol`
--
ALTER TABLE `personal_rol`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Personal_has_Rol_Rol1_idx` (`id_rol`),
  ADD KEY `fk_Personal_has_Rol_Personal_idx` (`id_personal`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `id_documentos_lineas` (`id_documentos_lineas`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `rol_documentos`
--
ALTER TABLE `rol_documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Rol_Documentos_Documentos1_idx` (`id_documentos`),
  ADD KEY `fk_Rol_Documentos_Rol1_idx` (`id_rol`);

--
-- Indices de la tabla `tipos_preguntas`
--
ALTER TABLE `tipos_preguntas`
  ADD PRIMARY KEY (`id_tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documentos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `documentos_lineas`
--
ALTER TABLE `documentos_lineas`
  MODIFY `id_documentos_lineas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `personal_rol`
--
ALTER TABLE `personal_rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol_documentos`
--
ALTER TABLE `rol_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_preguntas`
--
ALTER TABLE `tipos_preguntas`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documentos_lineas`
--
ALTER TABLE `documentos_lineas`
  ADD CONSTRAINT `fk_Documentos_Lineas_Documentos1` FOREIGN KEY (`id_documentos`) REFERENCES `documentos` (`id_documentos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Documentos_Lineas_Tipos_Preguntas1` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_preguntas` (`id_tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `personal_rol`
--
ALTER TABLE `personal_rol`
  ADD CONSTRAINT `fk_Personal_has_Rol_Personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Personal_has_Rol_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`id_documentos_lineas`) REFERENCES `documentos_lineas` (`id_documentos_lineas`),
  ADD CONSTRAINT `respuestas_ibfk_2` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`);

--
-- Filtros para la tabla `rol_documentos`
--
ALTER TABLE `rol_documentos`
  ADD CONSTRAINT `fk_Rol_Documentos_Documentos1` FOREIGN KEY (`id_documentos`) REFERENCES `documentos` (`id_documentos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Rol_Documentos_Rol1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(1000) NOT NULL,
  `abreviatura` varchar(1000) NOT NULL,
  `id_tutor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;
--COMMIT;

ALTER TABLE cursos
  ADD CONSTRAINT `fk_Cursos_Personal` FOREIGN KEY (`id_tutor`) REFERENCES `personal` (`id_personal`) ON DELETE NO ACTION ON UPDATE NO ACTION;


CREATE TABLE asignaturas (
id_asignatura INT(11) NOT NULL AUTO_INCREMENT,
nombre_asignatura VARCHAR(1000),
abreviatura VARCHAR(1000),
id_curso INT(11),
PRIMARY KEY (id_asignatura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE asignaturas
  ADD CONSTRAINT `fk_Asignaturas_Cursos` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
--
-- 
  
CREATE TABLE curso_documentos (
id INT(11) NOT NULL AUTO_INCREMENT,
id_curso INT(11),
id_documentos INT(11),
PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE curso_documentos
  ADD CONSTRAINT `fk_CursosDocumentos_Cursos` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE curso_documentos
  ADD CONSTRAINT `fk_CursosDocumentos_Documentos` FOREIGN KEY (`id_documentos`) REFERENCES `documentos` (`id_documentos`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
--
--
--

CREATE TABLE asignatura_documentos (
id INT(11) NOT NULL AUTO_INCREMENT,
id_asignatura INT(11),
id_documentos INT(11),
PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE asignatura_documentos
  ADD CONSTRAINT `fk_AsignaturaDocumentos_Asignatura` FOREIGN KEY (`id_asignatura`) REFERENCES `asignaturas` (`id_asignatura`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE asignatura_documentos
  ADD CONSTRAINT `fk_AsignaturaDocumentos_Documentos` FOREIGN KEY (`id_documentos`) REFERENCES `documentos` (`id_documentos`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
--
--
--

CREATE TABLE personal_curso (
id INT(11) NOT NULL AUTO_INCREMENT,
id_personal INT(11),
id_curso INT(11),
id_tutor INT(11),
PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE personal_curso
  ADD CONSTRAINT `fk_PersonalCurso_Personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE personal_curso
  ADD CONSTRAINT `fk_PersonalCurso_Cursos` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
  
--
--
--

CREATE TABLE personal_asignatura (
id INT(11) NOT NULL AUTO_INCREMENT,
id_personal INT(11),
id_asignatura INT(11),
PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE personal_asignatura
  ADD CONSTRAINT `fk_PersonalAsignatura_Personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE personal_asignatura
  ADD CONSTRAINT `fk_PersonalAsignatura_Asignatura` FOREIGN KEY (`id_asignatura`) REFERENCES `asignaturas` (`id_asignatura`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
  
--


--CONSULTA PARA SACAR EL INFORME DEL TANTO PER CENTO


SELECT cur.id_curso, cur.nombre_curso, cur.abreviatura as abreviatura_curso, tutor.nombre as tutor, per.nombre as profesor, asig.nombre_asignatura, asig.abreviatura as abreviatura_asignatura
FROM curso_documentos curdoc
INNER JOIN cursos cur ON cur.id_curso = curdoc.id_curso
INNER JOIN personal_curso percur ON percur.id_curso = cur.id_curso
INNER JOIN personal per ON per.id_personal = percur.id_personal
INNER JOIN personal_asignatura perasig ON perasig.id_personal = per.id_personal
INNER JOIN asignaturas asig ON asig.id_asignatura = perasig.id_asignatura
INNER JOIN personal tutor ON tutor.id_personal = cur.id_tutor
INNER JOIN documentos doc ON doc.id_documentos = curdoc.id_documentos
INNER JOIN documentos_lineas docli ON docli.id_documentos = doc.id_documentos
INNER JOIN respuestas res ON res.id_documentos_lineas = docli.id_documentos_lineas



--TABLAS DE IMPORTACIÓN CSV

CREATE TABLE importacion_roles(
codigo VARCHAR(1000),
anno VARCHAR(1000),
cargo VARCHAR(1000));

--
