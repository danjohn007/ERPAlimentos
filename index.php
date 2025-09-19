<?php
/**
 * Punto de entrada principal del sistema ERP Quesos
 * Implementa enrutamiento MVC y manejo de sesiones
 */

// Definir constante para permitir acceso
define('ERP_QUESOS', true);

// Configurar nombre de sesión antes de iniciar
if (session_status() === PHP_SESSION_NONE) {
    session_name('erp_quesos_session');
    session_start();
}

// Incluir archivos de configuración
require_once 'config/config.php';
require_once 'config/database.php';

// Autoloader simple para clases
spl_autoload_register(function ($class) {
    $paths = [
        CONTROLLERS_PATH . $class . '.php',
        MODELS_PATH . $class . '.php',
        __DIR__ . '/app/core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Clases del núcleo
require_once 'app/core/Controller.php';
require_once 'app/core/Model.php';
require_once 'app/core/View.php';
require_once 'app/core/Router.php';
require_once 'app/core/Auth.php';

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Fallback para el servidor de desarrollo de PHP (no procesa .htaccess)
if (empty($url) && isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Remover el script name del request URI si está presente
    if (strpos($requestUri, $scriptName) === 0) {
        $url = substr($requestUri, strlen($scriptName));
    } else {
        // Para servidor de desarrollo, tomar directamente el REQUEST_URI
        $url = $requestUri;
    }
    
    // Limpiar query string
    if (($pos = strpos($url, '?')) !== false) {
        $url = substr($url, 0, $pos);
    }
    
    // Limpiar barras
    $url = trim($url, '/');
}

// Inicializar router
$router = new Router();

// Definir rutas del sistema
$router->addRoute('', 'Auth', 'login');  // Cambiar a login primero
$router->addRoute('login', 'Auth', 'login');
$router->addRoute('logout', 'Auth', 'logout');
$router->addRoute('dashboard', 'Dashboard', 'index');

// Módulos principales
$router->addRoute('produccion', 'Produccion', 'index');
$router->addRoute('produccion/recetas', 'Produccion', 'recetas');
$router->addRoute('produccion/lotes', 'Produccion', 'lotes');
$router->addRoute('produccion/lotes/crear', 'Produccion', 'crearLote');
$router->addRoute('produccion/lotes/ver/(\d+)', 'Produccion', 'verLote');

$router->addRoute('materias-primas', 'MateriasPrimas', 'index');
$router->addRoute('materias-primas/proveedores', 'MateriasPrimas', 'proveedores');
$router->addRoute('materias-primas/inventario', 'MateriasPrimas', 'inventario');

$router->addRoute('calidad', 'Calidad', 'index');
$router->addRoute('calidad/analisis', 'Calidad', 'analisis');
$router->addRoute('calidad/trazabilidad', 'Calidad', 'trazabilidad');

$router->addRoute('inventario', 'Inventario', 'index');
$router->addRoute('inventario/productos', 'Inventario', 'productos');
$router->addRoute('inventario/movimientos', 'Inventario', 'movimientos');

$router->addRoute('ventas', 'Ventas', 'index');
$router->addRoute('ventas/clientes', 'Ventas', 'clientes');
$router->addRoute('ventas/ordenes', 'Ventas', 'ordenes');
$router->addRoute('ventas/facturacion', 'Ventas', 'facturacion');

$router->addRoute('compras', 'Compras', 'index');
$router->addRoute('compras/ordenes', 'Compras', 'ordenes');
$router->addRoute('compras/recepcion', 'Compras', 'recepcion');

$router->addRoute('finanzas', 'Finanzas', 'index');
$router->addRoute('finanzas/contabilidad', 'Finanzas', 'contabilidad');
$router->addRoute('finanzas/reportes', 'Finanzas', 'reportes');

$router->addRoute('rrhh', 'RecursosHumanos', 'index');
$router->addRoute('rrhh/empleados', 'RecursosHumanos', 'empleados');
$router->addRoute('rrhh/nomina', 'RecursosHumanos', 'nomina');

$router->addRoute('reportes', 'Reportes', 'index');
$router->addRoute('reportes/bi', 'Reportes', 'businessIntelligence');

$router->addRoute('admin', 'Admin', 'index');
$router->addRoute('admin/usuarios', 'Admin', 'usuarios');
$router->addRoute('admin/configuracion', 'Admin', 'configuracion');

// AJAX Routes
$router->addRoute('ajax/([^/]+)/([^/]+)', 'Ajax', 'handle');

// Manejar la solicitud
try {
    $router->handleRequest($url);
} catch (Exception $e) {
    // Manejar errores
    if (DEBUG_MODE) {
        echo '<div class="alert alert-danger">';
        echo '<h4>Error del Sistema</h4>';
        echo '<p><strong>Mensaje:</strong> ' . $e->getMessage() . '</p>';
        echo '<p><strong>Archivo:</strong> ' . $e->getFile() . '</p>';
        echo '<p><strong>Línea:</strong> ' . $e->getLine() . '</p>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger">';
        echo '<h4>Error</h4>';
        echo '<p>Ha ocurrido un error inesperado. Por favor, contacte al administrador.</p>';
        echo '</div>';
    }
}