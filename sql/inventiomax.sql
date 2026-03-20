-- =====================================================
-- INVENTIO MAX v9.0 - Estructura de Base de Datos
-- Sistema de Gestion de Inventario
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `inventiomax` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `inventiomax`;

-- --------------------------------------------------------
-- Tabla: user (Usuarios del sistema)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comision` decimal(10,2) DEFAULT 0.00,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `kind` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Admin, 2=Almacenista, 3=Vendedor, 4=Admin Sucursal',
  `stock_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario admin por defecto (password: admin123)
INSERT INTO `user` (`id`, `name`, `lastname`, `username`, `email`, `kind`, `password`, `status`, `created_at`) VALUES
(1, 'Administrador', 'Sistema', 'admin', 'admin@inventiomax.com', 1, '35c9d02cc42a687f2f43cfaf5e5a3df8de6f702c', 1, NOW())
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: stock (Sucursales/Almacenes)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `field1` varchar(255) DEFAULT NULL,
  `field2` varchar(255) DEFAULT NULL,
  `is_principal` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sucursal principal por defecto
INSERT INTO `stock` (`id`, `code`, `name`, `is_principal`, `created_at`) VALUES
(1, 'SUC001', 'Sucursal Principal', 1, NOW())
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: category (Categorias de productos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: brand (Marcas de productos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: product (Productos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `kind` tinyint(4) DEFAULT 1,
  `price_in` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_out` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_out2` decimal(10,2) DEFAULT 0.00,
  `price_out3` decimal(10,2) DEFAULT 0.00,
  `unit` varchar(50) DEFAULT 'PZA',
  `presentation` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inventary_min` int(11) DEFAULT 0,
  `width` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `expire_at` date DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: person (Clientes, Proveedores, Contactos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `email1` varchar(100) DEFAULT NULL,
  `phone1` varchar(50) DEFAULT NULL,
  `kind` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Cliente, 2=Proveedor, 3=Contacto',
  `is_active_access` tinyint(4) DEFAULT 0,
  `password` varchar(255) DEFAULT NULL,
  `has_credit` tinyint(4) DEFAULT 0,
  `credit_limit` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: operation_type (Tipos de operacion)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `operation_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tipos de operacion por defecto
INSERT INTO `operation_type` (`id`, `name`) VALUES
(1, 'entrada'),
(2, 'salida'),
(3, 'entrada-pendiente'),
(4, 'salida-pendiente'),
(5, 'devolucion'),
(6, 'traspaso')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: p (Estados de pago)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `p` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estados de pago por defecto
INSERT INTO `p` (`id`, `name`) VALUES
(1, 'Pagado'),
(2, 'Pendiente'),
(3, 'Cancelado'),
(4, 'Credito')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: d (Estados de entrega)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `d` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estados de entrega por defecto
INSERT INTO `d` (`id`, `name`) VALUES
(1, 'Entregado'),
(2, 'Pendiente'),
(3, 'Cancelado')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: f (Tipos de factura)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `f` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tipos de factura por defecto
INSERT INTO `f` (`id`, `name`) VALUES
(1, 'Factura'),
(2, 'Sin Factura'),
(3, 'Nota de Venta')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: sell (Ventas, Compras, Traspasos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sell` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_code` varchar(100) DEFAULT NULL,
  `invoice_file` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `stock_to_id` int(11) DEFAULT NULL,
  `stock_from_id` int(11) DEFAULT NULL,
  `sell_from_id` int(11) DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT 0.00,
  `f_id` int(11) DEFAULT NULL,
  `p_id` int(11) DEFAULT NULL,
  `d_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `cash` decimal(10,2) DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `operation_type_id` int(11) DEFAULT 2,
  `is_draft` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`),
  KEY `user_id` (`user_id`),
  KEY `stock_to_id` (`stock_to_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: operation (Operaciones de inventario)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `q` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_in` decimal(10,2) DEFAULT 0.00,
  `price_out` decimal(10,2) DEFAULT 0.00,
  `stock_id` int(11) DEFAULT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `operation_type_id` int(11) NOT NULL,
  `operation_from_id` int(11) DEFAULT NULL,
  `cut_id` int(11) DEFAULT NULL,
  `is_draft` tinyint(4) DEFAULT 0,
  `is_traspase` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `stock_id` (`stock_id`),
  KEY `sell_id` (`sell_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: configuration (Configuracion del sistema)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `short` varchar(50) NOT NULL,
  `val` text DEFAULT NULL,
  `short_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short` (`short`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Configuraciones por defecto
INSERT INTO `configuration` (`name`, `short`, `val`) VALUES
('Nombre de la Empresa', 'company_name', 'Mi Empresa'),
('Direccion', 'company_address', 'Direccion de la empresa'),
('Telefono', 'company_phone', '000-000-0000'),
('Moneda', 'currency', '$'),
('IVA', 'iva', '16'),
('Imagen de Reporte', 'report_image', '')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: message (Mensajes internos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `user_from` int(11) DEFAULT NULL,
  `user_to` int(11) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: payment (Pagos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sell_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `payment_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sell_id` (`sell_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: payment_type (Tipos de pago)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tipos de pago por defecto
INSERT INTO `payment_type` (`id`, `name`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta'),
(3, 'Transferencia'),
(4, 'Cheque')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Tabla: spend (Gastos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `spend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concept` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `date_at` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: saving (Depositos/Ajustes)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saving` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concept` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `kind` tinyint(4) DEFAULT 1,
  `date_at` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: box (Caja/Cortes)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `box` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `total_sells` decimal(10,2) DEFAULT 0.00,
  `total_res` decimal(10,2) DEFAULT 0.00,
  `total_spends` decimal(10,2) DEFAULT 0.00,
  `total_deposits` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `closed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: cut (Cortes de caja)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `closed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: price (Precios especiales)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
