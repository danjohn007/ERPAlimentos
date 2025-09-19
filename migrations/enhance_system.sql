-- ============================================
-- Mejoras al sistema ERP Alimentos
-- Actualización de tablas para soportar funcionalidad completa
-- ============================================

-- Tabla para órdenes de compra
CREATE TABLE IF NOT EXISTS `ordenes_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_orden` varchar(30) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha_orden` datetime NOT NULL,
  `fecha_entrega_esperada` date DEFAULT NULL,
  `fecha_entrega_real` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `impuestos` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `estado` enum('borrador','enviado','confirmado','recibido','cancelado') NOT NULL DEFAULT 'borrador',
  `estado_pago` enum('pendiente','parcial','pagado') NOT NULL DEFAULT 'pendiente',
  `saldo_pendiente` decimal(12,2) DEFAULT 0.00,
  `observaciones` text,
  `comprador_id` int(11) DEFAULT NULL,
  `autorizado_por` int(11) DEFAULT NULL,
  `fecha_autorizacion` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_orden` (`numero_orden`),
  KEY `fk_ordenes_compra_proveedor` (`proveedor_id`),
  KEY `fk_ordenes_compra_comprador` (`comprador_id`),
  KEY `fk_ordenes_compra_autorizador` (`autorizado_por`),
  CONSTRAINT `fk_ordenes_compra_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ordenes_compra_comprador` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ordenes_compra_autorizador` FOREIGN KEY (`autorizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Detalles de órdenes de compra
CREATE TABLE IF NOT EXISTS `orden_compra_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orden_compra_id` int(11) NOT NULL,
  `materia_prima_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,4) NOT NULL,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `cantidad_recibida` decimal(10,2) DEFAULT 0.00,
  `observaciones` text,
  PRIMARY KEY (`id`),
  KEY `fk_orden_compra_detalles_orden` (`orden_compra_id`),
  KEY `fk_orden_compra_detalles_materia` (`materia_prima_id`),
  CONSTRAINT `fk_orden_compra_detalles_orden` FOREIGN KEY (`orden_compra_id`) REFERENCES `ordenes_compra` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orden_compra_detalles_materia` FOREIGN KEY (`materia_prima_id`) REFERENCES `materias_primas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para seguimiento de costos de producción
CREATE TABLE IF NOT EXISTS `costos_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lote_produccion_id` int(11) NOT NULL,
  `materia_prima_id` int(11) NOT NULL,
  `cantidad_utilizada` decimal(10,4) NOT NULL,
  `costo_unitario` decimal(10,4) NOT NULL,
  `costo_total` decimal(12,2) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_costos_lote` (`lote_produccion_id`),
  KEY `fk_costos_materia` (`materia_prima_id`),
  CONSTRAINT `fk_costos_lote` FOREIGN KEY (`lote_produccion_id`) REFERENCES `lotes_produccion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_costos_materia` FOREIGN KEY (`materia_prima_id`) REFERENCES `materias_primas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para movimientos de inventario
CREATE TABLE IF NOT EXISTS `movimientos_inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimiento` enum('entrada','salida','ajuste','transferencia') NOT NULL,
  `tipo_item` enum('materia_prima','producto_terminado') NOT NULL,
  `item_id` int(11) NOT NULL,
  `lote` varchar(30) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `cantidad_anterior` decimal(10,2) NOT NULL,
  `cantidad_nueva` decimal(10,2) NOT NULL,
  `costo_unitario` decimal(10,4) DEFAULT NULL,
  `motivo` varchar(255) NOT NULL,
  `referencia_tipo` enum('orden_compra','orden_venta','lote_produccion','ajuste_manual') DEFAULT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `observaciones` text,
  `fecha_movimiento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_movimientos_tipo_item` (`tipo_item`, `item_id`),
  KEY `idx_movimientos_fecha` (`fecha_movimiento`),
  KEY `fk_movimientos_usuario` (`usuario_id`),
  CONSTRAINT `fk_movimientos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para cuentas contables
CREATE TABLE IF NOT EXISTS `cuentas_contables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('activo','pasivo','capital','ingreso','gasto') NOT NULL,
  `subtipo` varchar(50) DEFAULT NULL,
  `nivel` int(1) NOT NULL DEFAULT 1,
  `cuenta_padre_id` int(11) DEFAULT NULL,
  `es_movimiento` tinyint(1) NOT NULL DEFAULT 1,
  `saldo_inicial` decimal(15,2) DEFAULT 0.00,
  `saldo_actual` decimal(15,2) DEFAULT 0.00,
  `estado` enum('activa','inactiva') NOT NULL DEFAULT 'activa',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_cuentas_padre` (`cuenta_padre_id`),
  CONSTRAINT `fk_cuentas_padre` FOREIGN KEY (`cuenta_padre_id`) REFERENCES `cuentas_contables` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para asientos contables
CREATE TABLE IF NOT EXISTS `asientos_contables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_asiento` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('ingreso','gasto','ajuste','cierre') NOT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `concepto` text NOT NULL,
  `total_debe` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_haber` decimal(15,2) NOT NULL DEFAULT 0.00,
  `estado` enum('borrador','confirmado','anulado') NOT NULL DEFAULT 'borrador',
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_asiento` (`numero_asiento`),
  KEY `fk_asientos_usuario` (`usuario_id`),
  CONSTRAINT `fk_asientos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Detalles de asientos contables
CREATE TABLE IF NOT EXISTS `asiento_contable_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asiento_contable_id` int(11) NOT NULL,
  `cuenta_contable_id` int(11) NOT NULL,
  `debe` decimal(15,2) DEFAULT 0.00,
  `haber` decimal(15,2) DEFAULT 0.00,
  `concepto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_asiento_detalles_asiento` (`asiento_contable_id`),
  KEY `fk_asiento_detalles_cuenta` (`cuenta_contable_id`),
  CONSTRAINT `fk_asiento_detalles_asiento` FOREIGN KEY (`asiento_contable_id`) REFERENCES `asientos_contables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_asiento_detalles_cuenta` FOREIGN KEY (`cuenta_contable_id`) REFERENCES `cuentas_contables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar cuentas contables básicas
INSERT INTO `cuentas_contables` (`codigo`, `nombre`, `tipo`, `subtipo`, `nivel`, `es_movimiento`, `saldo_inicial`, `estado`) VALUES
-- ACTIVOS
('1000', 'ACTIVOS', 'activo', 'grupo', 1, 0, 0.00, 'activa'),
('1100', 'ACTIVO CIRCULANTE', 'activo', 'subgrupo', 2, 0, 0.00, 'activa'),
('1101', 'Caja', 'activo', 'efectivo', 3, 1, 10000.00, 'activa'),
('1102', 'Bancos', 'activo', 'efectivo', 3, 1, 50000.00, 'activa'),
('1103', 'Cuentas por Cobrar', 'activo', 'cuentas_cobrar', 3, 1, 0.00, 'activa'),
('1104', 'Inventario Materias Primas', 'activo', 'inventario', 3, 1, 25000.00, 'activa'),
('1105', 'Inventario Productos Terminados', 'activo', 'inventario', 3, 1, 35000.00, 'activa'),

-- PASIVOS
('2000', 'PASIVOS', 'pasivo', 'grupo', 1, 0, 0.00, 'activa'),
('2100', 'PASIVO CIRCULANTE', 'pasivo', 'subgrupo', 2, 0, 0.00, 'activa'),
('2101', 'Cuentas por Pagar', 'pasivo', 'cuentas_pagar', 3, 1, 15000.00, 'activa'),
('2102', 'Impuestos por Pagar', 'pasivo', 'impuestos', 3, 1, 5000.00, 'activa'),

-- CAPITAL
('3000', 'CAPITAL', 'capital', 'grupo', 1, 0, 0.00, 'activa'),
('3101', 'Capital Social', 'capital', 'capital_social', 2, 1, 100000.00, 'activa'),

-- INGRESOS
('4000', 'INGRESOS', 'ingreso', 'grupo', 1, 0, 0.00, 'activa'),
('4101', 'Ventas', 'ingreso', 'ventas', 2, 1, 0.00, 'activa'),

-- GASTOS
('5000', 'GASTOS', 'gasto', 'grupo', 1, 0, 0.00, 'activa'),
('5100', 'GASTOS DE OPERACIÓN', 'gasto', 'subgrupo', 2, 0, 0.00, 'activa'),
('5101', 'Costo de Ventas', 'gasto', 'costo_ventas', 3, 1, 0.00, 'activa'),
('5102', 'Sueldos y Salarios', 'gasto', 'nomina', 3, 1, 0.00, 'activa'),
('5103', 'Servicios', 'gasto', 'servicios', 3, 1, 0.00, 'activa'),
('5104', 'Materia Prima', 'gasto', 'materiales', 3, 1, 0.00, 'activa');

-- Insertar algunas órdenes de compra de ejemplo
INSERT INTO `ordenes_compra` (`numero_orden`, `proveedor_id`, `fecha_orden`, `fecha_entrega_esperada`, `subtotal`, `impuestos`, `total`, `estado`, `comprador_id`, `observaciones`) VALUES
('OC-20241201-001', 1, '2024-12-01 10:00:00', '2024-12-03', 5000.00, 800.00, 5800.00, 'enviado', 1, 'Orden regular de leche fresca'),
('OC-20241201-002', 2, '2024-12-01 14:30:00', '2024-12-05', 1200.00, 192.00, 1392.00, 'confirmado', 1, 'Cuajo para producción de queso manchego'),
('OC-20241202-003', 3, '2024-12-02 09:15:00', '2024-12-04', 800.00, 128.00, 928.00, 'borrador', 2, 'Empaques biodegradables nuevos');

-- Insertar detalles de órdenes de compra
INSERT INTO `orden_compra_detalles` (`orden_compra_id`, `materia_prima_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 1000.00, 5.00, 5000.00),
(2, 2, 24.00, 50.00, 1200.00),
(3, 4, 200.00, 4.00, 800.00);