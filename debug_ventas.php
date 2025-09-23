<?php
define('ERP_QUESOS', true);
require_once 'config/config.php';
require_once 'config/database.php';

// Obtener conexión
$db = Database::getInstance();

echo "<h2>Debug de Ventas del Mes</h2>";

// Consulta actual que está en el dashboard
$query = "SELECT 
    COALESCE(SUM(total_orden), 0) as total_ventas,
    COUNT(*) as total_ordenes 
FROM ordenes_venta 
WHERE YEAR(fecha_orden) = YEAR(CURDATE()) 
    AND MONTH(fecha_orden) = MONTH(CURDATE())";

echo "<h3>Query ejecutada:</h3>";
echo "<pre>$query</pre>";

$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h3>Resultado crudo de la consulta:</h3>";
echo "<pre>";
print_r($result);
echo "</pre>";

echo "<h3>Análisis de tipos de datos:</h3>";
echo "total_ventas tipo: " . gettype($result['total_ventas']) . "<br>";
echo "total_ventas valor: " . var_export($result['total_ventas'], true) . "<br>";
echo "total_ordenes tipo: " . gettype($result['total_ordenes']) . "<br>";
echo "total_ordenes valor: " . var_export($result['total_ordenes'], true) . "<br>";

echo "<h3>Después de conversión:</h3>";
$ventasFloat = floatval($result['total_ventas']);
$ordenesInt = intval($result['total_ordenes']);

echo "ventasFloat: " . var_export($ventasFloat, true) . "<br>";
echo "ordenesInt: " . var_export($ordenesInt, true) . "<br>";

// Probar formatMoney
function formatMoney($amount) {
    return number_format((float)$amount, 2, '.', ',') . ' ' . '$';
}

echo "<h3>Formato final:</h3>";
echo "formatMoney(ventasFloat): " . formatMoney($ventasFloat) . "<br>";

// Ver todas las órdenes del mes actual
echo "<h3>Órdenes del mes actual:</h3>";
$queryDetalle = "SELECT * FROM ordenes_venta 
WHERE YEAR(fecha_orden) = YEAR(CURDATE()) 
    AND MONTH(fecha_orden) = MONTH(CURDATE())";

$stmt2 = $db->prepare($queryDetalle);
$stmt2->execute();
$ordenes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($ordenes);
echo "</pre>";

?>