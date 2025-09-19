-- ==================================================
-- Datos de muestra para el módulo de proveedores
-- ERP Alimentos - Gestión de Proveedores
-- ==================================================

-- Insertar proveedores de muestra si no existen
INSERT IGNORE INTO `proveedores` (`id`, `codigo`, `nombre`, `tipo`, `contacto`, `telefono`, `email`, `direccion`, `rfc`, `certificaciones`, `estado`) VALUES
(1, 'PROV001', 'Lácteos San Rafael', 'leche', 'María González', '555-0101', 'ventas@lacteossanrafael.com', 'Carretera Nacional Km 45, Jalisco', 'LSR850315ABC', 'TIF,HACCP,ISO 22000', 'activo'),
(2, 'PROV002', 'Distribuidora de Insumos Químicos del Norte', 'insumos', 'Carlos Ramírez', '555-0102', 'carlos@diqnorte.com', 'Av. Industrial 123, Monterrey, NL', 'DIN920420XYZ', 'ISO 9001,ISO 14001', 'activo'),
(3, 'PROV003', 'Empaques y Envases del Bajío', 'empaques', 'Ana López', '555-0103', 'ana.lopez@empaqesbajio.mx', 'Blvd. de las Torres 456, Querétaro, Qro', 'EEB800210DEF', '', 'activo'),
(4, 'PROV004', 'Servicios de Transporte Refrigerado', 'servicios', 'Roberto Méndez', '555-0104', 'logistica@str.com.mx', 'Calle Comercio 789, CDMX', 'STR750608GHI', 'TIF', 'activo'),
(5, 'PROV005', 'Granja Lechera Los Alamos', 'leche', 'Laura Hernández', '555-0105', 'laura@granjalosalamos.com', 'Rancho Los Alamos S/N, Michoacán', 'GLA880912JKL', 'TIF,HACCP', 'activo'),
(6, 'PROV006', 'Suministros Industriales del Centro', 'insumos', 'Miguel Torres', '555-0106', 'miguel@sumicentro.com', 'Parque Industrial Norte, León, Gto', 'SIC920315MNO', 'ISO 9001', 'inactivo'),
(7, 'PROV007', 'Envases Biodegradables México', 'empaques', 'Patricia Silva', '555-0107', 'patricia@envasesbio.mx', 'Eco Park 321, Puebla, Pue', 'EBM850720PQR', 'FSC,Biodegradable', 'activo'),
(8, 'PROV008', 'Cooperativa Lechera Regional', 'leche', 'José Martínez', '555-0108', 'jose@cooplactea.coop', 'Zona Rural Km 12, Hidalgo', 'CLR900815STU', 'TIF', 'activo');

-- Actualizar campo de términos de pago si no existe
ALTER TABLE `proveedores` 
ADD COLUMN IF NOT EXISTS `terminos_pago` enum('contado','credito','mixto') NOT NULL DEFAULT 'contado',
ADD COLUMN IF NOT EXISTS `dias_credito` int(11) DEFAULT 0;

-- Actualizar los proveedores existentes con términos de pago
UPDATE `proveedores` SET 
  `terminos_pago` = 'credito',
  `dias_credito` = 30
WHERE `id` IN (1, 2, 5, 8);

UPDATE `proveedores` SET 
  `terminos_pago` = 'contado',
  `dias_credito` = 0
WHERE `id` IN (3, 4, 7);

UPDATE `proveedores` SET 
  `terminos_pago` = 'mixto',
  `dias_credito` = 15
WHERE `id` = 6;

-- Insertar algunas materias primas de ejemplo asociadas a proveedores
INSERT IGNORE INTO `materias_primas` (`id`, `codigo`, `nombre`, `tipo`, `descripcion`, `unidad_medida`, `stock_actual`, `stock_minimo`, `stock_maximo`, `costo_unitario`, `proveedor_principal_id`, `fecha_caducidad`, `ubicacion_almacen`, `estado`) VALUES
(1, 'MP001', 'Leche Fresca Pasteurizada', 'leche', 'Leche fresca pasteurizada de alta calidad', 'litro', 500.00, 100.00, 1000.00, 12.50, 1, '2024-01-15', 'Cámara Fría A1', 'activo'),
(2, 'MP002', 'Cuajo Líquido', 'insumo', 'Cuajo líquido para elaboración de quesos', 'litro', 25.00, 5.00, 50.00, 45.00, 2, '2024-06-30', 'Almacén B2', 'activo'),
(3, 'MP003', 'Sal Refinada Yodada', 'insumo', 'Sal refinada para uso alimentario', 'kg', 75.50, 20.00, 200.00, 8.50, 2, NULL, 'Almacén B1', 'activo'),
(4, 'MP004', 'Envases Plásticos 500g', 'empaque', 'Envases plásticos para queso 500g', 'pieza', 2500, 500, 5000, 1.25, 3, NULL, 'Almacén C1', 'activo'),
(5, 'MP005', 'Leche Orgánica Certificada', 'leche', 'Leche orgánica con certificación', 'litro', 150.00, 50.00, 500.00, 18.00, 5, '2024-01-20', 'Cámara Fría A2', 'activo'),
(6, 'MP006', 'Etiquetas Adhesivas', 'empaque', 'Etiquetas para productos lácteos', 'pieza', 1200, 200, 3000, 0.35, 7, NULL, 'Almacén C2', 'activo');

-- Crear tabla de evaluación de proveedores si no existe
CREATE TABLE IF NOT EXISTS `evaluacion_proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proveedor_id` int(11) NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `calidad_producto` int(1) NOT NULL DEFAULT 5 COMMENT '1-5 scale',
  `puntualidad_entrega` int(1) NOT NULL DEFAULT 5 COMMENT '1-5 scale',
  `competitividad_precio` int(1) NOT NULL DEFAULT 5 COMMENT '1-5 scale',
  `servicio_cliente` int(1) NOT NULL DEFAULT 5 COMMENT '1-5 scale',
  `promedio_general` decimal(3,2) NOT NULL DEFAULT 5.00,
  `observaciones` text,
  `evaluador_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_evaluacion_proveedor` (`proveedor_id`),
  KEY `fk_evaluacion_evaluador` (`evaluador_id`),
  CONSTRAINT `fk_evaluacion_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_evaluacion_evaluador` FOREIGN KEY (`evaluador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunas evaluaciones de ejemplo
INSERT IGNORE INTO `evaluacion_proveedores` (`proveedor_id`, `fecha_evaluacion`, `calidad_producto`, `puntualidad_entrega`, `competitividad_precio`, `servicio_cliente`, `promedio_general`, `observaciones`) VALUES
(1, '2023-12-01', 5, 4, 4, 5, 4.50, 'Excelente calidad de leche, muy buen servicio'),
(2, '2023-12-01', 4, 5, 3, 4, 4.00, 'Buenos productos químicos, precios algo altos'),
(5, '2023-12-01', 5, 5, 4, 5, 4.75, 'Leche orgánica de excelente calidad'),
(8, '2023-12-01', 4, 4, 5, 4, 4.25, 'Buena relación calidad-precio');

COMMIT;