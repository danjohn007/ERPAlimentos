<?php
// Test script to verify database setup and basic functionality
require_once 'config/config.php';
require_once 'config/database.php';

echo "<h2>ERP Quesos - Database Test</h2>";

try {
    $db = Database::getInstance();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test ordenes_venta table exists
    $stmt = $db->prepare("SHOW TABLES LIKE 'ordenes_venta'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ ordenes_venta table exists</p>";
        
        // Count orders
        $count = $db->queryOne("SELECT COUNT(*) as total FROM ordenes_venta");
        echo "<p>Total orders in database: " . $count['total'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ ordenes_venta table missing</p>";
    }
    
    // Test orden_venta_detalles table exists
    $stmt = $db->prepare("SHOW TABLES LIKE 'orden_venta_detalles'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ orden_venta_detalles table exists</p>";
        
        // Count order details
        $count = $db->queryOne("SELECT COUNT(*) as total FROM orden_venta_detalles");
        echo "<p>Total order details in database: " . $count['total'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ orden_venta_detalles table missing</p>";
    }
    
    // Test clients
    $count = $db->queryOne("SELECT COUNT(*) as total FROM clientes WHERE estado = 'activo'");
    echo "<p>Active clients: " . $count['total'] . "</p>";
    
    // Test products
    $count = $db->queryOne("SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'");
    echo "<p>Active products: " . $count['total'] . "</p>";
    
    // Test monthly sales (dashboard query)
    $ventasMes = $db->queryOne("
        SELECT COUNT(*) as total_ordenes, COALESCE(SUM(total), 0) as total_ventas
        FROM ordenes_venta 
        WHERE MONTH(fecha_orden) = MONTH(CURDATE()) 
        AND YEAR(fecha_orden) = YEAR(CURDATE())
        AND estado != 'cancelado'
    ");
    
    if ($ventasMes) {
        echo "<p>This month - Orders: " . $ventasMes['total_ordenes'] . ", Sales: $" . number_format($ventasMes['total_ventas'], 2) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>