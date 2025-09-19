-- Tablas adicionales necesarias para el ERP

-- Ingredientes de recetas
CREATE TABLE IF NOT EXISTS `receta_ingredientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receta_id` int(11) NOT NULL,
  `materia_prima_id` int(11) NOT NULL,
  `cantidad` decimal(10,4) NOT NULL,
  `orden` int(11) DEFAULT 1,
  `observaciones` text,
  PRIMARY KEY (`id`),
  KEY `fk_receta_ingredientes_receta` (`receta_id`),
  KEY `fk_receta_ingredientes_materia_prima` (`materia_prima_id`),
  CONSTRAINT `fk_receta_ingredientes_receta` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_receta_ingredientes_materia_prima` FOREIGN KEY (`materia_prima_id`) REFERENCES `materias_primas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Órdenes de venta
CREATE TABLE IF NOT EXISTS `ordenes_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_orden` varchar(30) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha_orden` datetime NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `descuento` decimal(12,2) DEFAULT 0.00,
  `impuestos` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `estado` enum('pendiente','proceso','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `estado_pago` enum('pendiente','parcial','pagado') NOT NULL DEFAULT 'pendiente',
  `saldo_pendiente` decimal(12,2) DEFAULT 0.00,
  `observaciones` text,
  `vendedor_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_orden` (`numero_orden`),
  KEY `fk_ordenes_venta_cliente` (`cliente_id`),
  KEY `fk_ordenes_venta_vendedor` (`vendedor_id`),
  CONSTRAINT `fk_ordenes_venta_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ordenes_venta_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Detalles de órdenes de venta
CREATE TABLE IF NOT EXISTS `orden_venta_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orden_venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `observaciones` text,
  PRIMARY KEY (`id`),
  KEY `fk_orden_detalles_orden` (`orden_venta_id`),
  KEY `fk_orden_detalles_producto` (`producto_id`),
  CONSTRAINT `fk_orden_detalles_orden` FOREIGN KEY (`orden_venta_id`) REFERENCES `ordenes_venta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orden_detalles_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Análisis de calidad
CREATE TABLE IF NOT EXISTS `analisis_calidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('materia_prima','producto_terminado','proceso') NOT NULL,
  `item_id` int(11) NOT NULL,
  `lote_produccion_id` int(11) DEFAULT NULL,
  `fecha_analisis` datetime NOT NULL,
  `analista_id` int(11) DEFAULT NULL,
  `ph` decimal(3,1) DEFAULT NULL,
  `humedad` decimal(5,2) DEFAULT NULL,
  `temperatura` decimal(4,1) DEFAULT NULL,
  `grasa` decimal(5,2) DEFAULT NULL,
  `proteina` decimal(5,2) DEFAULT NULL,
  `sal` decimal(5,2) DEFAULT NULL,
  `microbiologia` text,
  `observaciones` text,
  `resultado` enum('conforme','no_conforme','requiere_revision') NOT NULL DEFAULT 'conforme',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_analisis_lote` (`lote_produccion_id`),
  KEY `fk_analisis_analista` (`analista_id`),
  CONSTRAINT `fk_analisis_lote` FOREIGN KEY (`lote_produccion_id`) REFERENCES `lotes_produccion` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_analisis_analista` FOREIGN KEY (`analista_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alertas del sistema
CREATE TABLE IF NOT EXISTS `alertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('stock_bajo','vencimiento','calidad','produccion','general') NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `prioridad` enum('baja','media','alta','critica') NOT NULL DEFAULT 'media',
  `estado` enum('activa','leida','archivada') NOT NULL DEFAULT 'activa',
  `usuario_id` int(11) DEFAULT NULL,
  `referencia_tabla` varchar(50) DEFAULT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_leida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_alertas_usuario` (`usuario_id`),
  KEY `idx_alertas_estado` (`estado`),
  KEY `idx_alertas_tipo` (`tipo`),
  CONSTRAINT `fk_alertas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;