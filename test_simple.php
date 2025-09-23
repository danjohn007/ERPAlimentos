<?php
define('ERP_QUESOS', true);
require_once 'config/config.php';

echo 'CURRENCY_SYMBOL: ' . CURRENCY_SYMBOL . PHP_EOL;
echo 'number_format(19800.00): ' . number_format(19800.00, 2, '.', ',') . PHP_EOL;
echo 'formatMoney simulado: ' . CURRENCY_SYMBOL . number_format(19800.00, 2, '.', ',') . PHP_EOL;

// Probar con el valor que puede estar en BD
$testValues = [19800.00, '19800.00', 19800, '19800'];

foreach ($testValues as $val) {
    echo "Valor: $val (tipo: " . gettype($val) . ") -> " . CURRENCY_SYMBOL . number_format($val, 2, '.', ',') . PHP_EOL;
}
?>