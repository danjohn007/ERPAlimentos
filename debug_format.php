<?php
/**
 * Debug de formatMoney para encontrar el problema
 */

define('ERP_QUESOS', true);
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'app/core/View.php';

echo "<h2>Debug de formatMoney</h2>";

// Crear instancia de View
$view = new View();

// Valores de prueba
$valores = [
    19800.00,
    19800,
    "19800.00",
    "19800",
    floatval("19800.00")
];

echo "<h3>Pruebas con diferentes tipos de datos:</h3>";

foreach ($valores as $i => $valor) {
    echo "<p><strong>Valor " . ($i+1) . ":</strong></p>";
    echo "Valor original: " . var_export($valor, true) . "<br>";
    echo "Tipo: " . gettype($valor) . "<br>";
    echo "formatMoney(): " . $view->formatMoney($valor) . "<br>";
    echo "number_format directo: " . number_format($valor, 2, '.', ',') . "<br>";
    echo "Con símbolo: $" . number_format($valor, 2, '.', ',') . "<br>";
    echo "<hr>";
}

// Ahora vamos a probar con los datos reales de la base de datos
echo "<h3>Datos reales de la base de datos:</h3>";

try {
    $db = Database::getInstance();
    $query = "SELECT 
        COALESCE(SUM(total), 0) as total_ventas,
        COUNT(*) as total_ordenes 
    FROM ordenes_venta 
    WHERE YEAR(fecha_orden) = YEAR(CURDATE()) 
        AND MONTH(fecha_orden) = MONTH(CURDATE())
        AND estado != 'cancelado'";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Resultado crudo: " . var_export($result, true) . "<br>";
    
    $total_ventas = $result['total_ventas'];
    echo "Total ventas (crudo): " . var_export($total_ventas, true) . "<br>";
    echo "Tipo: " . gettype($total_ventas) . "<br>";
    
    // Conversión manual
    $total_float = floatval($total_ventas);
    echo "Después de floatval(): " . var_export($total_float, true) . "<br>";
    
    // formatMoney
    echo "formatMoney(): " . $view->formatMoney($total_ventas) . "<br>";
    echo "formatMoney() con floatval: " . $view->formatMoney($total_float) . "<br>";
    
    // Verificar si hay algún problema con la conversión
    echo "strlen del valor: " . strlen((string)$total_ventas) . "<br>";
    echo "Contiene caracteres extraños: ";
    for ($i = 0; $i < strlen((string)$total_ventas); $i++) {
        $char = substr((string)$total_ventas, $i, 1);
        echo ord($char) . "(" . $char . ") ";
    }
    echo "<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Verificar la constante CURRENCY_SYMBOL
echo "<h3>Configuración:</h3>";
echo "CURRENCY_SYMBOL: " . (defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'NO DEFINIDO') . "<br>";

?>