<?php
/**
 * Debug AJAX Routes
 */

define('ERP_QUESOS', true);

// Configurar nombre de sesión antes de iniciar
if (session_status() === PHP_SESSION_NONE) {
    session_name('erp_quesos_session');
    session_start();
}

require_once 'config/config.php';

echo "<h1>Debug AJAX Routes</h1>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>Test URL:</strong> " . BASE_URL . "/ajax/produccion/crear-lote</p>";

// Probar acceso directo
echo "<h2>Probando acceso directo al controlador AJAX:</h2>";

// Simular $_GET['url'] para el router
$_GET['url'] = 'ajax/produccion/crear-lote';

echo "<p>Simulating URL: ajax/produccion/crear-lote</p>";

// Test router configuration
echo "<h2>Testing router configuration...</h2>";
echo "<p>Should redirect to AjaxController->handle('produccion', 'crear-lote')</p>";
?>