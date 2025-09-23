<?php
define('ERP_QUESOS', true);
require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    
    echo "<h2>Debug Ventas - Datos de BD</h2>";
    
    // Consulta actual del dashboard
    $query = "SELECT COUNT(*) as total_ordenes, COALESCE(SUM(total), 0) as total_ventas
    FROM ordenes_venta 
    WHERE YEAR(fecha_orden) = YEAR(CURDATE()) 
    AND MONTH(fecha_orden) = MONTH(CURDATE())
    AND estado != 'cancelado'";
    
    echo "<h3>Query:</h3><pre>$query</pre>";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Resultado:</h3>";
    echo "<pre>";
    var_dump($result);
    echo "</pre>";
    
    $total_ventas = $result['total_ventas'];
    echo "<h3>Análisis del campo total_ventas:</h3>";
    echo "Valor crudo: " . var_export($total_ventas, true) . "<br>";
    echo "Tipo: " . gettype($total_ventas) . "<br>";
    echo "Valor como string: '" . (string)$total_ventas . "'<br>";
    echo "Longitud: " . strlen((string)$total_ventas) . "<br>";
    
    // Análisis byte por byte
    echo "Análisis byte por byte: ";
    $str = (string)$total_ventas;
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str[$i];
        echo "[" . ord($char) . ":" . $char . "] ";
    }
    echo "<br>";
    
    // Formateo
    echo "<h3>Formateo:</h3>";
    echo "floatval(): " . floatval($total_ventas) . "<br>";
    echo "number_format directo: " . number_format($total_ventas, 2, '.', ',') . "<br>";
    echo "formatMoney: $" . number_format($total_ventas, 2, '.', ',') . "<br>";
    
    // Ver todas las órdenes del mes
    echo "<h3>Órdenes del mes:</h3>";
    $queryDetalle = "SELECT id, total, fecha_orden, estado FROM ordenes_venta 
    WHERE YEAR(fecha_orden) = YEAR(CURDATE()) 
    AND MONTH(fecha_orden) = MONTH(CURDATE())
    ORDER BY fecha_orden DESC";
    
    $stmt2 = $db->prepare($queryDetalle);
    $stmt2->execute();
    $ordenes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Total</th><th>Fecha</th><th>Estado</th></tr>";
    foreach ($ordenes as $orden) {
        echo "<tr>";
        echo "<td>" . $orden['id'] . "</td>";
        echo "<td>" . $orden['total'] . " (tipo: " . gettype($orden['total']) . ")</td>";
        echo "<td>" . $orden['fecha_orden'] . "</td>";
        echo "<td>" . $orden['estado'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>