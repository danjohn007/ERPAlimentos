-- ============================================
-- Script de instalación para ERP Quesos
-- Base de datos MySQL 5.7+
-- ============================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `erp_quesos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `erp_quesos`;

-- ============================================
-- TABLA: usuarios
-- ============================================
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `rol` enum('admin','supervisor','operador','vendedor','contabilidad') NOT NULL DEFAULT 'operador',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: proveedores
-- ============================================
CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('leche','insumos','empaques','servicios') NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text,
  `rfc` varchar(13) DEFAULT NULL,
  `certificaciones` text,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: clientes
-- ============================================
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('mayorista','minorista','distribuidor','tienda_propia') NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text,
  `rfc` varchar(13) DEFAULT NULL,
  `credito_limite` decimal(12,2) DEFAULT 0.00,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: productos
-- ============================================
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('queso_fresco','queso_semicurado','queso_curado','subproducto') NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `unidad_medida` enum('kg','g','pieza','litro') NOT NULL DEFAULT 'kg',
  `precio_venta` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo_produccion` decimal(10,2) DEFAULT 0.00,
  `stock_minimo` decimal(10,2) DEFAULT 0.00,
  `stock_actual` decimal(10,2) DEFAULT 0.00,
  `dias_caducidad` int(11) DEFAULT NULL,
  `requiere_refrigeracion` tinyint(1) NOT NULL DEFAULT 1,
  `temperatura_almacen` decimal(4,1) DEFAULT NULL,
  `humedad_almacen` decimal(5,2) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: recetas
-- ============================================
CREATE TABLE `recetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `rendimiento_litros_leche` decimal(8,2) NOT NULL,
  `rendimiento_kg_queso` decimal(8,2) NOT NULL,
  `tiempo_preparacion` int(11) NOT NULL COMMENT 'Minutos',
  `tiempo_maduracion` int(11) DEFAULT NULL COMMENT 'Días',
  `temperatura_proceso` decimal(4,1) DEFAULT NULL,
  `ph_optimo` decimal(3,1) DEFAULT NULL,
  `humedad_maduracion` decimal(5,2) DEFAULT NULL,
  `instrucciones` text,
  `version` varchar(10) NOT NULL DEFAULT '1.0',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_recetas_producto` (`producto_id`),
  CONSTRAINT `fk_recetas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: materias_primas
-- ============================================
CREATE TABLE `materias_primas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('leche_vaca','leche_cabra','leche_oveja','cuajo','sal','cultivo_lactico','conservante','empaque') NOT NULL,
  `unidad_medida` enum('litro','kg','g','ml','unidad') NOT NULL,
  `costo_unitario` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `stock_actual` decimal(10,2) DEFAULT 0.00,
  `stock_minimo` decimal(10,2) DEFAULT 0.00,
  `dias_caducidad` int(11) DEFAULT NULL,
  `requiere_refrigeracion` tinyint(1) NOT NULL DEFAULT 1,
  `temperatura_almacen` decimal(4,1) DEFAULT NULL,
  `proveedor_principal_id` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_materias_primas_proveedor` (`proveedor_principal_id`),
  CONSTRAINT `fk_materias_primas_proveedor` FOREIGN KEY (`proveedor_principal_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: lotes_produccion
-- ============================================
CREATE TABLE `lotes_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_lote` varchar(30) NOT NULL,
  `receta_id` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `cantidad_programada` decimal(10,2) NOT NULL,
  `cantidad_producida` decimal(10,2) DEFAULT 0.00,
  `litros_leche_utilizados` decimal(10,2) DEFAULT 0.00,
  `rendimiento_real` decimal(8,4) DEFAULT NULL,
  `temperatura_proceso` decimal(4,1) DEFAULT NULL,
  `ph_inicial` decimal(3,1) DEFAULT NULL,
  `ph_final` decimal(3,1) DEFAULT NULL,
  `humedad_inicial` decimal(5,2) DEFAULT NULL,
  `humedad_final` decimal(5,2) DEFAULT NULL,
  `operador_id` int(11) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `estado` enum('programado','en_proceso','terminado','rechazado') NOT NULL DEFAULT 'programado',
  `observaciones` text,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_lote` (`numero_lote`),
  KEY `fk_lotes_receta` (`receta_id`),
  KEY `fk_lotes_operador` (`operador_id`),
  KEY `fk_lotes_supervisor` (`supervisor_id`),
  CONSTRAINT `fk_lotes_receta` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lotes_operador` FOREIGN KEY (`operador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_lotes_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA: inventario
-- ============================================
CREATE TABLE `inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('materia_prima','producto_terminado') NOT NULL,
  `item_id` int(11) NOT NULL,
  `lote` varchar(30) DEFAULT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo_unitario` decimal(10,4) DEFAULT 0.0000,
  `costo_total` decimal(12,2) DEFAULT 0.00,
  `ubicacion` varchar(50) DEFAULT NULL,
  `temperatura_almacen` decimal(4,1) DEFAULT NULL,
  `estado` enum('disponible','reservado','vencido','dañado') NOT NULL DEFAULT 'disponible',
  `proveedor_id` int(11) DEFAULT NULL,
  `lote_produccion_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tipo_item` (`tipo`, `item_id`),
  KEY `idx_lote` (`lote`),
  KEY `idx_fecha_caducidad` (`fecha_caducidad`),
  KEY `fk_inventario_proveedor` (`proveedor_id`),
  KEY `fk_inventario_lote_produccion` (`lote_produccion_id`),
  CONSTRAINT `fk_inventario_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_inventario_lote_produccion` FOREIGN KEY (`lote_produccion_id`) REFERENCES `lotes_produccion` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERTAR DATOS DE EJEMPLO
-- ============================================

-- Usuarios (contraseñas: admin123, supervisor123, operador123)
INSERT INTO `usuarios` (`username`, `email`, `password`, `nombre`, `apellidos`, `rol`, `estado`) VALUES
('admin', 'admin@erpquesos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'del Sistema', 'admin', 'activo'),
('supervisor1', 'supervisor@erpquesos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Carlos', 'Rodríguez', 'supervisor', 'activo'),
('operador1', 'operador@erpquesos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María Elena', 'González', 'operador', 'activo');

-- Proveedores
INSERT INTO `proveedores` (`codigo`, `nombre`, `tipo`, `contacto`, `telefono`, `email`, `direccion`, `rfc`, `certificaciones`, `estado`) VALUES
('PROV001', 'Lechería San Miguel', 'leche', 'Miguel Hernández', '555-0001', 'contacto@lecheriasanmiguel.com', 'Carretera Federal 123, Zona Rural', 'LSM850301ABC', 'HACCP, TIF', 'activo'),
('PROV002', 'Distribuidora de Cuajos Del Norte', 'insumos', 'Ana López', '555-0002', 'ventas@cuajosdelnorte.com', 'Av. Industrial 456, Col. Centro', 'DCN920415DEF', 'ISO 9001', 'activo'),
('PROV003', 'Empaques Biodegradables SA', 'empaques', 'Roberto Silva', '555-0003', 'info@empaquesbio.com', 'Blvd. Ecológico 789, Zona Industrial', 'EBS040712GHI', 'FSC, Biodegradable', 'activo');

-- Clientes
INSERT INTO `clientes` (`codigo`, `nombre`, `tipo`, `contacto`, `telefono`, `email`, `direccion`, `rfc`, `credito_limite`, `descuento_porcentaje`, `estado`) VALUES
('CLI001', 'Supermercados La Económica', 'mayorista', 'Carmen Ruiz', '555-1001', 'compras@laeconomica.com', 'Av. Principal 123, Centro', 'SLE770301JKL', 50000.00, 5.00, 'activo'),
('CLI002', 'Tienda Orgánica Natural', 'minorista', 'Pedro Martínez', '555-1002', 'pedidos@organicanatural.com', 'Calle Verde 456, Col. Ecológica', 'TON880415MNO', 15000.00, 2.50, 'activo'),
('CLI003', 'Distribuidora Regional Lácteos', 'distribuidor', 'Laura García', '555-1003', 'distribucion@regionallacteos.com', 'Zona Industrial 789, Sector 5', 'DRL930712PQR', 100000.00, 8.00, 'activo');

-- Productos
INSERT INTO `productos` (`codigo`, `nombre`, `tipo`, `categoria`, `unidad_medida`, `precio_venta`, `costo_produccion`, `stock_minimo`, `stock_actual`, `dias_caducidad`, `requiere_refrigeracion`, `temperatura_almacen`, `humedad_almacen`, `estado`) VALUES
('QUE001', 'Queso Fresco Ranchero', 'queso_fresco', 'Frescos', 'kg', 85.00, 45.00, 50.00, 120.50, 15, 1, 4.0, 85.00, 'activo'),
('QUE002', 'Queso Oaxaca Artesanal', 'queso_fresco', 'Frescos', 'kg', 95.00, 52.00, 30.00, 85.25, 20, 1, 4.0, 80.00, 'activo'),
('QUE003', 'Queso Manchego Semicurado', 'queso_semicurado', 'Semicurados', 'kg', 150.00, 78.00, 20.00, 45.75, 45, 1, 8.0, 75.00, 'activo'),
('QUE004', 'Queso Añejo Curado', 'queso_curado', 'Curados', 'kg', 220.00, 95.00, 15.00, 28.50, 180, 1, 12.0, 70.00, 'activo');

-- Materias Primas
INSERT INTO `materias_primas` (`codigo`, `nombre`, `tipo`, `unidad_medida`, `costo_unitario`, `stock_actual`, `stock_minimo`, `dias_caducidad`, `requiere_refrigeracion`, `temperatura_almacen`, `proveedor_principal_id`, `estado`) VALUES
('MP001', 'Leche Entera de Vaca', 'leche_vaca', 'litro', 12.5000, 850.00, 200.00, 3, 1, 4.0, 1, 'activo'),
('MP002', 'Cuajo Líquido Premium', 'cuajo', 'ml', 0.8500, 2500.00, 500.00, 90, 1, 4.0, 2, 'activo'),
('MP003', 'Sal Marina Fina', 'sal', 'kg', 15.0000, 125.00, 25.00, 365, 0, 20.0, 2, 'activo'),
('MP004', 'Cultivo Láctico Mesófilo', 'cultivo_lactico', 'g', 2.5000, 850.00, 100.00, 60, 1, -18.0, 2, 'activo');

-- Recetas
INSERT INTO `recetas` (`producto_id`, `codigo`, `nombre`, `descripcion`, `rendimiento_litros_leche`, `rendimiento_kg_queso`, `tiempo_preparacion`, `tiempo_maduracion`, `temperatura_proceso`, `ph_optimo`, `humedad_maduracion`, `instrucciones`, `version`, `estado`) VALUES
(1, 'REC001', 'Receta Queso Fresco Ranchero', 'Receta tradicional para queso fresco de alta calidad', 10.00, 1.20, 180, 0, 32.0, 6.2, 85.00, '1. Calentar leche a 32°C\n2. Agregar cultivo láctico\n3. Reposar 45 min\n4. Agregar cuajo\n5. Cortar cuajada\n6. Prensar 2 horas', '1.0', 'activo'),
(2, 'REC002', 'Receta Queso Oaxaca Artesanal', 'Queso hilado tradicional estilo Oaxaca', 8.50, 1.00, 240, 0, 35.0, 6.0, 80.00, '1. Calentar leche a 35°C\n2. Acidificar con cultivo\n3. Cuajar y cortar\n4. Hilar en agua caliente\n5. Formar bolas', '1.0', 'activo'),
(3, 'REC003', 'Receta Manchego Semicurado', 'Queso tipo manchego con maduración controlada', 12.00, 1.50, 300, 45, 30.0, 6.4, 75.00, '1. Pasteurizar leche\n2. Enfriar a 30°C\n3. Inocular cultivos\n4. Cuajar\n5. Prensar 12h\n6. Salar\n7. Madurar 45 días', '1.0', 'activo');

-- Lotes de Producción
INSERT INTO `lotes_produccion` (`numero_lote`, `receta_id`, `fecha_inicio`, `fecha_fin`, `cantidad_programada`, `cantidad_producida`, `litros_leche_utilizados`, `rendimiento_real`, `temperatura_proceso`, `ph_inicial`, `ph_final`, `operador_id`, `supervisor_id`, `estado`, `observaciones`) VALUES
('LOT-20240101-001', 1, '2024-01-15 08:00:00', '2024-01-15 11:00:00', 50.00, 48.50, 420.00, 0.1155, 32.0, 6.6, 6.2, 3, 2, 'terminado', 'Lote exitoso, rendimiento dentro de parámetros'),
('LOT-20240102-001', 2, '2024-01-16 09:00:00', '2024-01-16 13:00:00', 30.00, 28.75, 260.00, 0.1106, 35.0, 6.5, 6.0, 3, 2, 'terminado', 'Hilado perfecto, textura excelente'),
('LOT-20240103-001', 3, '2024-01-17 07:00:00', NULL, 25.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 3, 2, 'programado', 'Lote programado para hoy');

-- Inventario
INSERT INTO `inventario` (`tipo`, `item_id`, `lote`, `fecha_entrada`, `fecha_caducidad`, `cantidad`, `costo_unitario`, `costo_total`, `ubicacion`, `temperatura_almacen`, `estado`, `proveedor_id`, `lote_produccion_id`) VALUES
('materia_prima', 1, 'MP-LEC-20240115', '2024-01-15', '2024-01-18', 850.00, 12.5000, 10625.00, 'Tanque Refrigerado A1', 4.0, 'disponible', 1, NULL),
('materia_prima', 2, 'MP-CUJ-20240110', '2024-01-10', '2024-04-09', 2500.00, 0.8500, 2125.00, 'Refrigerador Insumos B2', 4.0, 'disponible', 2, NULL),
('materia_prima', 3, 'MP-SAL-20231220', '2023-12-20', '2024-12-19', 125.00, 15.0000, 1875.00, 'Almacén Secos C1', 20.0, 'disponible', 2, NULL),
('producto_terminado', 1, 'LOT-20240101-001', '2024-01-15', '2024-01-30', 48.50, 45.00, 2182.50, 'Cámara Fría 1', 4.0, 'disponible', NULL, 1),
('producto_terminado', 2, 'LOT-20240102-001', '2024-01-16', '2024-02-05', 28.75, 52.00, 1495.00, 'Cámara Fría 2', 4.0, 'disponible', NULL, 2);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;