-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 27-11-2025 a las 04:42:10
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
-- Base de datos: `sistema_recompensas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `actividad_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `puntos_otorgados` int(11) NOT NULL COMMENT 'Puntos que se otorgan por esta actividad',
  `tipo` enum('compra','referido','reseña','visita','otro') DEFAULT 'otro',
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`actividad_id`, `nombre`, `descripcion`, `puntos_otorgados`, `tipo`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Registro de Usuario', 'Puntos de bienvenida por registrarse', 100, 'otro', 1, '2025-10-15 20:19:13', '2025-10-15 20:19:13'),
(2, 'Primera Compra', 'Puntos por realizar primera compra', 200, 'compra', 1, '2025-10-15 20:19:13', '2025-10-15 20:19:13'),
(3, 'Referir un Amigo', 'Puntos por referir a otro usuario', 150, 'referido', 1, '2025-10-15 20:19:13', '2025-10-15 20:19:13'),
(4, 'Visita Mensual', 'Puntos por visitar la plataforma', 50, 'visita', 1, '2025-10-15 20:19:13', '2025-10-15 20:19:13'),
(5, 'Dejar una Reseña', 'Puntos por dejar una reseña', 75, 'reseña', 1, '2025-10-15 20:19:13', '2025-10-15 20:19:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_usuarios`
--

CREATE TABLE `actividades_usuarios` (
  `actividad_usuario_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `actividad_id` bigint(20) UNSIGNED NOT NULL,
  `puntos_ganados` int(11) NOT NULL COMMENT 'Puntos ganados en esta actividad',
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `administrador_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL COMMENT 'Ruta de la foto de perfil',
  `rol` enum('super_administrador','administrador','moderador') DEFAULT 'administrador',
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_eliminacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`administrador_id`, `nombre`, `apellido`, `email`, `usuario`, `password`, `telefono`, `foto`, `rol`, `activo`, `ultimo_acceso`, `fecha_creacion`, `fecha_actualizacion`, `fecha_eliminacion`) VALUES
(1, 'Admin', 'Sistema', 'admin@rewards.com', 'admin', '123', NULL, NULL, 'super_administrador', 1, '2025-10-17 00:09:18', '2025-10-15 20:19:13', '2025-10-17 00:09:18', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canjes_recompensas`
--

CREATE TABLE `canjes_recompensas` (
  `canje_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `recompensa_id` bigint(20) UNSIGNED NOT NULL,
  `puntos_utilizados` int(11) NOT NULL COMMENT 'Puntos utilizados en el canje',
  `estado` enum('pendiente','aprobado','entregado','cancelado') DEFAULT 'pendiente',
  `codigo_canje` varchar(20) NOT NULL COMMENT 'Código único de canje',
  `notas` text DEFAULT NULL,
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `aprobado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `canjes_recompensas`
--

INSERT INTO `canjes_recompensas` (`canje_id`, `usuario_id`, `recompensa_id`, `puntos_utilizados`, `estado`, `codigo_canje`, `notas`, `fecha_aprobacion`, `fecha_entrega`, `aprobado_por`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, 1, 2, 'aprobado', 'GNYU3WYODS', NULL, NULL, NULL, NULL, '2025-10-16 03:40:55', '2025-10-29 16:04:54'),
(2, 1, 1, 2, 'pendiente', 'SAWJTYFM51', NULL, NULL, NULL, NULL, '2025-10-16 03:42:22', '2025-10-16 03:42:22'),
(3, 1, 1, 2, 'pendiente', 'UNIVYEB0RL', NULL, NULL, NULL, NULL, '2025-10-16 03:49:17', '2025-10-16 03:49:17'),
(4, 1, 1, 2, 'pendiente', '2WAIADQMOR', NULL, NULL, NULL, NULL, '2025-10-16 11:58:41', '2025-10-16 11:58:41'),
(5, 4, 1, 2, 'pendiente', 'DF5ILAZOKJ', NULL, NULL, NULL, NULL, '2025-10-16 12:10:29', '2025-10-16 12:10:29'),
(6, 4, 1, 2, 'pendiente', 'HUEXDWFUPW', NULL, NULL, NULL, NULL, '2025-10-16 12:31:51', '2025-10-16 12:31:51'),
(7, 4, 1, 2, 'pendiente', 'QZ4MLLXYDM', NULL, NULL, NULL, NULL, '2025-10-16 12:33:57', '2025-10-16 12:33:57'),
(8, 4, 1, 2, 'pendiente', 'CQ44AWXECW', NULL, NULL, NULL, NULL, '2025-10-16 12:35:51', '2025-10-16 12:35:51'),
(9, 4, 1, 2, 'pendiente', '07F4KTEC8A', NULL, NULL, NULL, NULL, '2025-10-16 12:38:46', '2025-10-16 12:38:46'),
(10, 4, 1, 2, 'pendiente', 'LWTSHYNMSG', NULL, NULL, NULL, NULL, '2025-10-16 12:40:47', '2025-10-16 12:40:47'),
(11, 4, 1, 2, 'pendiente', '3H6GUJOLEI', NULL, NULL, NULL, NULL, '2025-10-16 12:43:54', '2025-10-16 12:43:54'),
(12, 4, 1, 2, 'pendiente', 'UZ7K2L6BDE', NULL, NULL, NULL, NULL, '2025-10-16 12:45:34', '2025-10-16 12:45:34'),
(13, 4, 1, 2, 'pendiente', 'WBE7TJIBNS', NULL, NULL, NULL, NULL, '2025-10-16 12:49:02', '2025-10-16 12:49:02'),
(14, 4, 1, 2, 'pendiente', 'SHPGRQ4BLE', NULL, NULL, NULL, NULL, '2025-10-16 12:59:26', '2025-10-16 12:59:26'),
(15, 4, 1, 2, 'pendiente', '7JMPGRNUYY', NULL, NULL, NULL, NULL, '2025-10-16 13:54:14', '2025-10-16 13:54:14'),
(16, 2, 1, 2, 'pendiente', 'O9AK0W2EAZ', NULL, NULL, NULL, NULL, '2025-10-16 14:00:19', '2025-10-16 14:00:19'),
(17, 4, 1, 2, 'pendiente', 'HL1R8OHUJT', NULL, NULL, NULL, NULL, '2025-10-16 14:03:05', '2025-10-16 14:03:05'),
(18, 4, 1, 2, 'pendiente', 'BDKXGHXAAP', NULL, NULL, NULL, NULL, '2025-10-16 23:46:19', '2025-10-16 23:46:19'),
(19, 4, 1, 2, 'pendiente', 'QC3N6IC9NK', NULL, NULL, NULL, NULL, '2025-10-17 05:25:48', '2025-10-17 05:25:48'),
(20, 5, 1, 2, 'pendiente', '7J3XJVVI6B', NULL, NULL, NULL, NULL, '2025-10-17 08:52:10', '2025-10-17 08:52:10'),
(21, 4, 1, 2, 'pendiente', 'RYIK6URYQJ', NULL, NULL, NULL, NULL, '2025-10-17 22:01:38', '2025-10-17 22:01:38'),
(22, 4, 1, 2, 'pendiente', 'A4TTSNR5IU', NULL, NULL, NULL, NULL, '2025-10-17 22:27:48', '2025-10-17 22:27:48'),
(23, 4, 1, 2, 'pendiente', '1KLTMH1HI6', NULL, NULL, NULL, NULL, '2025-10-17 22:28:55', '2025-10-17 22:28:55'),
(24, 4, 1, 2, 'aprobado', 'GCVG70EXCO', NULL, NULL, NULL, NULL, '2025-10-17 22:50:53', '2025-10-29 16:05:14'),
(25, 4, 1, 2, 'pendiente', 'AAXZAP8LCI', NULL, NULL, NULL, NULL, '2025-10-29 22:12:31', '2025-10-29 22:12:31'),
(26, 4, 1, 2, 'pendiente', 'L5ATKCTOXZ', NULL, NULL, NULL, NULL, '2025-11-25 07:20:53', '2025-11-25 07:20:53'),
(27, 4, 1, 2, 'pendiente', 'NGC5KO1NPH', NULL, NULL, NULL, NULL, '2025-11-27 08:39:00', '2025-11-27 08:39:00'),
(28, 4, 1, 2, 'pendiente', '1HNJZUM7TY', NULL, NULL, NULL, NULL, '2025-11-27 08:51:36', '2025-11-27 08:51:36'),
(29, 4, 1, 2, 'pendiente', 'BDNM1JGVHP', NULL, NULL, NULL, NULL, '2025-11-27 09:02:37', '2025-11-27 09:02:37'),
(30, 4, 1, 2, 'pendiente', '13VXAR6GK1', NULL, NULL, NULL, NULL, '2025-11-27 09:05:45', '2025-11-27 09:05:45'),
(31, 4, 1, 2, 'pendiente', 'J9ITMQBHMM', NULL, NULL, NULL, NULL, '2025-11-27 09:25:01', '2025-11-27 09:25:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recompensas`
--

CREATE TABLE `recompensas` (
  `recompensa_id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `puntos_requeridos` int(11) NOT NULL COMMENT 'Puntos necesarios para canjear',
  `imagen` varchar(255) DEFAULT 'principal.jpeg' COMMENT 'Imagen de la recompensa',
  `categoria` varchar(50) DEFAULT NULL COMMENT 'Categoría: descuentos, productos, servicios, etc.',
  `inventario` int(11) DEFAULT 0 COMMENT 'Cantidad disponible',
  `activo` tinyint(1) DEFAULT 1,
  `fecha_expiracion` date DEFAULT NULL COMMENT 'Fecha de vencimiento de la oferta',
  `terminos_condiciones` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_eliminacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recompensas`
--

INSERT INTO `recompensas` (`recompensa_id`, `titulo`, `descripcion`, `puntos_requeridos`, `imagen`, `categoria`, `inventario`, `activo`, `fecha_expiracion`, `terminos_condiciones`, `fecha_creacion`, `fecha_actualizacion`, `fecha_eliminacion`) VALUES
(1, 'Descuento del 10% en el Pago del Predial', 'Otorga al ciudadano un descuento del 10% sobre el monto total del impuesto predial correspondiente al año en curso, fomentando el pago puntual de obligaciones municipales.', 2, 'recom1.jpg', 'descuentos', 69, 1, NULL, NULL, '2025-10-15 20:19:13', '2025-11-27 09:25:02', NULL),
(2, 'Descuento del 15% en Tenencia Vehicular', 'Permite aplicar una reducción del 15% en el pago de la tenencia o uso de vehículo particular, beneficiando a los contribuyentes cumplidos', 1500, 'recom2v.jpg', 'servicios', 50, 1, NULL, NULL, '2025-10-15 20:19:13', '2025-10-29 15:39:55', NULL),
(3, 'Reducción del 20% en la Revalidación de Licencia de Conducir', 'Aplica un descuento del 20% en el costo de renovación o revalidación de licencias de conducir, como incentivo para mantener la documentación al día.', 2000, 'recom3.jpg', 'productos', 30, 1, NULL, NULL, '2025-10-15 20:19:13', '2025-10-29 15:41:04', NULL),
(4, 'Exención del Recargo por Pago Tardío del Agua', 'El ciudadano puede condonar los recargos generados por pagos atrasados del servicio de agua potable, regularizando su situación sin multas adicionales.', 1200, 'recom5.jpg', 'servicios', 40, 1, NULL, NULL, '2025-10-15 20:19:13', '2025-10-29 15:42:56', NULL),
(5, 'Bonificación del 25% en Derechos de Recolección de Basura', 'Se concede una bonificación del 25% sobre los derechos anuales por recolección y disposición de residuos sólidos urbanos.', 800, 'recom4.jpg', 'descuentos', 75, 1, NULL, NULL, '2025-10-15 20:19:13', '2025-10-29 15:42:19', NULL),
(6, 'Descuento del 30% en Multas de Tránsito', 'Permite aplicar un descuento del 30% sobre el importe total de una infracción vial, siempre que el contribuyente no sea reincidente.', 22, 'recom2.png', 'descuentos', 6, 1, '1000-03-31', 'f5ff5f5', '2025-10-16 13:43:19', '2025-10-29 15:41:28', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones_puntos`
--

CREATE TABLE `transacciones_puntos` (
  `transaccion_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('ganado','canjeado','expirado','ajustado') NOT NULL COMMENT 'Tipo de transacción',
  `puntos` int(11) NOT NULL COMMENT 'Cantidad de puntos (positivo o negativo)',
  `descripcion` varchar(255) NOT NULL COMMENT 'Descripción de la transacción',
  `recompensa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `administrador_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transacciones_puntos`
--

INSERT INTO `transacciones_puntos` (`transaccion_id`, `usuario_id`, `tipo`, `puntos`, `descripcion`, `recompensa_id`, `administrador_id`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 2, 'ganado', 100, 'Puntos de bienvenida por registro', NULL, NULL, '2025-10-16 02:44:31', '2025-10-16 02:44:31'),
(2, 1, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 03:40:55', '2025-10-16 03:40:55'),
(3, 1, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 03:42:22', '2025-10-16 03:42:22'),
(4, 1, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 03:49:17', '2025-10-16 03:49:17'),
(5, 3, 'ganado', 100, 'Puntos de bienvenida por registro', NULL, NULL, '2025-10-16 11:30:07', '2025-10-16 11:30:07'),
(6, 1, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 11:58:41', '2025-10-16 11:58:41'),
(7, 4, 'ganado', 100, 'Puntos de bienvenida por registro', NULL, NULL, '2025-10-16 12:07:47', '2025-10-16 12:07:47'),
(8, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:10:29', '2025-10-16 12:10:29'),
(9, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:31:51', '2025-10-16 12:31:51'),
(10, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:33:57', '2025-10-16 12:33:57'),
(11, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:35:51', '2025-10-16 12:35:51'),
(12, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:38:46', '2025-10-16 12:38:46'),
(13, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:40:47', '2025-10-16 12:40:47'),
(14, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:43:54', '2025-10-16 12:43:54'),
(15, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:45:34', '2025-10-16 12:45:34'),
(16, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:49:02', '2025-10-16 12:49:02'),
(17, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 12:59:26', '2025-10-16 12:59:26'),
(18, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 13:54:14', '2025-10-16 13:54:14'),
(19, 2, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 14:00:19', '2025-10-16 14:00:19'),
(20, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 14:03:05', '2025-10-16 14:03:05'),
(21, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-16 23:46:19', '2025-10-16 23:46:19'),
(22, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-17 05:25:48', '2025-10-17 05:25:48'),
(23, 5, 'ganado', 100, 'Puntos de bienvenida por registro', NULL, NULL, '2025-10-17 08:51:38', '2025-10-17 08:51:38'),
(24, 5, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-17 08:52:10', '2025-10-17 08:52:10'),
(25, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-17 22:01:38', '2025-10-17 22:01:38'),
(26, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-17 22:27:48', '2025-10-17 22:27:48'),
(27, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-17 22:28:55', '2025-10-17 22:28:55'),
(28, 4, 'canjeado', -2, 'Canje de recompensa: Descuento 10% en Lavado', 1, NULL, '2025-10-17 22:50:53', '2025-10-17 22:50:53'),
(29, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-10-29 22:12:31', '2025-10-29 22:12:31'),
(30, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-11-25 07:20:53', '2025-11-25 07:20:53'),
(31, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-11-27 08:39:00', '2025-11-27 08:39:00'),
(32, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-11-27 08:51:36', '2025-11-27 08:51:36'),
(33, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-11-27 09:02:37', '2025-11-27 09:02:37'),
(34, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-11-27 09:05:45', '2025-11-27 09:05:45'),
(35, 4, 'canjeado', -2, 'Canje de recompensa: Descuento del 10% en el Pago del Predial', 1, NULL, '2025-11-27 09:25:02', '2025-11-27 09:25:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `numero_placa` varchar(7) NOT NULL COMMENT 'Placa del vehículo - usado para login',
  `password` varchar(8) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `marca_vehiculo` varchar(50) DEFAULT NULL,
  `modelo_vehiculo` varchar(50) DEFAULT NULL,
  `anio_vehiculo` year(4) DEFAULT NULL,
  `puntos` int(11) DEFAULT 0 COMMENT 'Puntos acumulados del usuario',
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_eliminacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `apellido`, `email`, `numero_placa`, `password`, `telefono`, `marca_vehiculo`, `modelo_vehiculo`, `anio_vehiculo`, `puntos`, `activo`, `ultimo_acceso`, `fecha_creacion`, `fecha_actualizacion`, `fecha_eliminacion`) VALUES
(1, 'yyyy', 'nynyny', 'n@mnjcn.com', '55556B', '123', '56565', 'TYTT', 'HTHTHT', '1999', 2, 1, '2025-10-17 00:05:58', '2025-10-15 20:40:44', '2025-10-17 00:05:58', NULL),
(2, 'HHH', 'THTH', 'javiirving91ff5@gmail.com', 'AAA123', '123', '455455', 'GTT', 'GGTG', '1999', 98, 1, '2025-10-16 13:59:04', '2025-10-16 02:44:31', '2025-10-16 14:00:19', NULL),
(3, 'thgtth', 'tgtgtgt', 'javiirving915itc@gmail.com', '45454', '123', '545454', '4gtg', 'ggr', '1999', 100, 1, NULL, '2025-10-16 11:30:07', '2025-10-31 22:37:18', NULL),
(4, 'Fatima', 'Silva', 'javiirving915@gmail.com', 'GTO-345', '123', '55444', 'TOYOTA', '345X', '1999', 50, 1, '2025-11-27 09:24:50', '2025-10-16 12:07:47', '2025-11-27 09:25:01', NULL),
(5, 'dcdfc', 'fvfv', 'kelnysilva74@gmail.com', '666', 'zwLcgjj1', 'yuyuy', 'mjhkh', 'jhmbm', '1999', 98, 1, NULL, '2025-10-17 08:51:38', '2025-10-17 08:52:10', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`actividad_id`),
  ADD KEY `idx_activo` (`activo`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- Indices de la tabla `actividades_usuarios`
--
ALTER TABLE `actividades_usuarios`
  ADD PRIMARY KEY (`actividad_usuario_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_actividad_id` (`actividad_id`),
  ADD KEY `idx_fecha_creacion` (`fecha_creacion`);

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`administrador_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `idx_usuario` (`usuario`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_activo` (`activo`);

--
-- Indices de la tabla `canjes_recompensas`
--
ALTER TABLE `canjes_recompensas`
  ADD PRIMARY KEY (`canje_id`),
  ADD UNIQUE KEY `codigo_canje` (`codigo_canje`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_recompensa_id` (`recompensa_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_codigo_canje` (`codigo_canje`),
  ADD KEY `fk_canjes_administrador` (`aprobado_por`);

--
-- Indices de la tabla `recompensas`
--
ALTER TABLE `recompensas`
  ADD PRIMARY KEY (`recompensa_id`),
  ADD KEY `idx_activo` (`activo`),
  ADD KEY `idx_categoria` (`categoria`),
  ADD KEY `idx_puntos` (`puntos_requeridos`);

--
-- Indices de la tabla `transacciones_puntos`
--
ALTER TABLE `transacciones_puntos`
  ADD PRIMARY KEY (`transaccion_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_fecha_creacion` (`fecha_creacion`),
  ADD KEY `fk_transacciones_puntos_recompensa` (`recompensa_id`),
  ADD KEY `fk_transacciones_puntos_administrador` (`administrador_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `numero_placa` (`numero_placa`),
  ADD KEY `idx_numero_placa` (`numero_placa`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_activo` (`activo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `actividad_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `actividades_usuarios`
--
ALTER TABLE `actividades_usuarios`
  MODIFY `actividad_usuario_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `administrador_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `canjes_recompensas`
--
ALTER TABLE `canjes_recompensas`
  MODIFY `canje_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `recompensas`
--
ALTER TABLE `recompensas`
  MODIFY `recompensa_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `transacciones_puntos`
--
ALTER TABLE `transacciones_puntos`
  MODIFY `transaccion_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades_usuarios`
--
ALTER TABLE `actividades_usuarios`
  ADD CONSTRAINT `fk_actividades_usuarios_actividad` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`actividad_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_actividades_usuarios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `canjes_recompensas`
--
ALTER TABLE `canjes_recompensas`
  ADD CONSTRAINT `fk_canjes_administrador` FOREIGN KEY (`aprobado_por`) REFERENCES `administradores` (`administrador_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_canjes_recompensa` FOREIGN KEY (`recompensa_id`) REFERENCES `recompensas` (`recompensa_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_canjes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transacciones_puntos`
--
ALTER TABLE `transacciones_puntos`
  ADD CONSTRAINT `fk_transacciones_puntos_administrador` FOREIGN KEY (`administrador_id`) REFERENCES `administradores` (`administrador_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_transacciones_puntos_recompensa` FOREIGN KEY (`recompensa_id`) REFERENCES `recompensas` (`recompensa_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_transacciones_puntos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
