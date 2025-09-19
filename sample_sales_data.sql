-- Sample sales data for ERP Quesos
-- This script adds realistic sales orders and details to demonstrate the system

USE `erp_quesos`;

-- Insert sample sales orders from the current month
INSERT INTO `ordenes_venta` (
    `numero_orden`, 
    `cliente_id`, 
    `fecha_orden`, 
    `fecha_entrega`, 
    `subtotal`, 
    `descuento`, 
    `impuestos`, 
    `total`, 
    `estado`, 
    `estado_pago`, 
    `saldo_pendiente`, 
    `observaciones`, 
    `vendedor_id`
) VALUES
-- Órdenes de diciembre 2024
('ORD-20241201-0001', 1, '2024-12-01 09:30:00', '2024-12-02', 2125.00, 106.25, 323.40, 2342.15, 'entregado', 'pagado', 0.00, 'Entrega puntual al supermercado', 1),
('ORD-20241203-0002', 2, '2024-12-03 14:15:00', '2024-12-04', 950.00, 23.75, 148.20, 1074.45, 'entregado', 'pagado', 0.00, 'Cliente satisfecho con la calidad', 1),
('ORD-20241205-0003', 3, '2024-12-05 11:00:00', '2024-12-06', 3300.00, 264.00, 485.76, 3521.76, 'entregado', 'parcial', 1521.76, 'Pago parcial de $2000', 1),
('ORD-20241208-0004', 1, '2024-12-08 16:45:00', '2024-12-09', 1700.00, 85.00, 258.40, 1873.40, 'entregado', 'pagado', 0.00, 'Segunda orden del mes', 1),
('ORD-20241210-0005', 2, '2024-12-10 10:20:00', '2024-12-11', 760.00, 19.00, 118.56, 859.56, 'proceso', 'pendiente', 859.56, 'En preparación', 1),
('ORD-20241212-0006', 3, '2024-12-12 13:30:00', '2024-12-13', 2200.00, 176.00, 323.84, 2347.84, 'proceso', 'pendiente', 2347.84, 'Orden grande, requiere coordinación', 1),
('ORD-20241215-0007', 1, '2024-12-15 09:00:00', '2024-12-16', 1425.00, 71.25, 216.60, 1570.35, 'pendiente', 'pendiente', 1570.35, 'Orden reciente', 1),
('ORD-20241217-0008', 2, '2024-12-17 15:30:00', '2024-12-18', 855.00, 21.38, 133.38, 966.99, 'pendiente', 'pendiente', 966.99, 'Pedido para fin de semana', 1),
('ORD-20241218-0009', 3, '2024-12-18 11:15:00', '2024-12-19', 2750.00, 220.00, 404.80, 2934.80, 'pendiente', 'pendiente', 2934.80, 'Pedido especial navideño', 1),

-- Órdenes de noviembre 2024 (para historial)
('ORD-20241102-0001', 1, '2024-11-02 10:00:00', '2024-11-03', 1890.00, 94.50, 287.28, 2082.78, 'entregado', 'pagado', 0.00, 'Orden noviembre', 1),
('ORD-20241105-0002', 2, '2024-11-05 14:00:00', '2024-11-06', 1260.00, 31.50, 196.56, 1425.06, 'entregado', 'pagado', 0.00, 'Orden noviembre', 1),
('ORD-20241108-0003', 3, '2024-11-08 09:30:00', '2024-11-09', 3150.00, 252.00, 463.68, 3361.68, 'entregado', 'pagado', 0.00, 'Orden noviembre', 1),
('ORD-20241112-0004', 1, '2024-11-12 16:00:00', '2024-11-13', 2100.00, 105.00, 319.20, 2314.20, 'entregado', 'pagado', 0.00, 'Orden noviembre', 1),
('ORD-20241115-0005', 2, '2024-11-15 11:30:00', '2024-11-16', 945.00, 23.63, 147.42, 1068.79, 'entregado', 'pagado', 0.00, 'Orden noviembre', 1),
('ORD-20241118-0006', 3, '2024-11-18 13:45:00', '2024-11-19', 2800.00, 224.00, 411.84, 2987.84, 'entregado', 'pagado', 0.00, 'Orden noviembre', 1),

-- Órdenes de octubre 2024 (para historial)
('ORD-20241003-0001', 1, '2024-10-03 09:15:00', '2024-10-04', 1575.00, 78.75, 239.76, 1735.01, 'entregado', 'pagado', 0.00, 'Orden octubre', 1),
('ORD-20241007-0002', 2, '2024-10-07 12:30:00', '2024-10-08', 1120.00, 28.00, 174.72, 1266.72, 'entregado', 'pagado', 0.00, 'Orden octubre', 1),
('ORD-20241010-0003', 3, '2024-10-10 15:20:00', '2024-10-11', 2625.00, 210.00, 386.40, 2801.40, 'entregado', 'pagado', 0.00, 'Orden octubre', 1);

-- Insert order details for all orders
-- Order 1 details
INSERT INTO `orden_venta_detalles` (`orden_venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`, `observaciones`) VALUES
(1, 1, 15.00, 85.00, 0.00, 1275.00, 'Queso fresco para supermercado'),
(1, 2, 10.00, 95.00, 50.00, 900.00, 'Descuento por volumen'),

-- Order 2 details  
(2, 3, 5.00, 150.00, 0.00, 750.00, 'Queso semicurado premium'),
(2, 1, 4.00, 85.00, 50.00, 290.00, 'Queso fresco con descuento'),

-- Order 3 details
(3, 4, 10.00, 220.00, 0.00, 2200.00, 'Queso curado especial'),
(3, 3, 8.00, 150.00, 100.00, 1100.00, 'Descuento distribuidor'),

-- Order 4 details
(4, 1, 12.00, 85.00, 0.00, 1020.00, 'Segundo pedido del mes'),
(4, 2, 8.00, 95.00, 80.00, 680.00, 'Descuento fidelidad'),

-- Order 5 details
(5, 1, 6.00, 85.00, 0.00, 510.00, 'Pedido pequeño'),
(5, 3, 2.00, 150.00, 50.00, 250.00, 'Muestra de semicurado'),

-- Order 6 details
(6, 4, 8.00, 220.00, 0.00, 1760.00, 'Queso curado premium'),
(6, 2, 5.00, 95.00, 35.00, 440.00, 'Oaxaca artesanal'),

-- Order 7 details
(7, 1, 10.00, 85.00, 0.00, 850.00, 'Pedido estándar'),
(7, 3, 4.00, 150.00, 25.00, 575.00, 'Manchego semicurado'),

-- Order 8 details
(8, 2, 7.00, 95.00, 0.00, 665.00, 'Oaxaca para restaurant'),
(8, 1, 3.00, 85.00, 65.00, 190.00, 'Complemento pedido'),

-- Order 9 details
(9, 4, 9.00, 220.00, 0.00, 1980.00, 'Especial navideño'),
(9, 3, 6.00, 150.00, 130.00, 770.00, 'Variedad navideña'),

-- November orders details
(10, 1, 14.00, 85.00, 0.00, 1190.00, 'Noviembre pedido 1'),
(10, 2, 8.00, 95.00, 60.00, 700.00, 'Con descuento'),

(11, 3, 7.00, 150.00, 0.00, 1050.00, 'Semicurado noviembre'),
(11, 1, 3.00, 85.00, 45.00, 210.00, 'Fresco complemento'),

(12, 4, 12.00, 220.00, 0.00, 2640.00, 'Gran pedido noviembre'),
(12, 2, 6.00, 95.00, 60.00, 510.00, 'Oaxaca adicional'),

(13, 1, 16.00, 85.00, 0.00, 1360.00, 'Pedido grande fresco'),
(13, 3, 5.00, 150.00, 10.00, 740.00, 'Semicurado premium'),

(14, 2, 9.00, 95.00, 0.00, 855.00, 'Oaxaca exclusivo'),
(14, 1, 2.00, 85.00, 80.00, 90.00, 'Muestra fresco'),

(15, 4, 10.00, 220.00, 0.00, 2200.00, 'Curado especial noviembre'),
(15, 3, 4.00, 150.00, 0.00, 600.00, 'Semicurado acompañante'),

-- October orders details
(16, 1, 11.00, 85.00, 0.00, 935.00, 'Octubre inicio'),
(16, 2, 7.00, 95.00, 25.00, 640.00, 'Oaxaca octubre'),

(17, 3, 6.00, 150.00, 0.00, 900.00, 'Semicurado octubre'),
(17, 1, 3.00, 85.00, 35.00, 220.00, 'Fresco complemento'),

(18, 4, 9.00, 220.00, 0.00, 1980.00, 'Curado octubre'),
(18, 3, 5.00, 150.00, 105.00, 645.00, 'Semicurado con descuento');

-- Update product stock after sales
UPDATE productos SET stock_actual = stock_actual - 50 WHERE id = 1; -- Queso Fresco
UPDATE productos SET stock_actual = stock_actual - 35 WHERE id = 2; -- Queso Oaxaca  
UPDATE productos SET stock_actual = stock_actual - 28 WHERE id = 3; -- Queso Manchego
UPDATE productos SET stock_actual = stock_actual - 25 WHERE id = 4; -- Queso Añejo