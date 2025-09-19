<?php
/**
 * Configuración principal del sistema ERP Quesos
 * Configuración automática de URL base y credenciales de base de datos
 */

// Prevenir acceso directo
defined('ERP_QUESOS') or die('Acceso denegado');

// Configuración automática de URL base
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    
    // Limpiar el path
    $path = str_replace('\\', '/', $path);
    $path = rtrim($path, '/');
    
    return $protocol . $host . $path;
}

// Configuración global
define('BASE_URL', getBaseUrl());
define('APP_NAME', 'ERP Quesos');
define('APP_VERSION', '1.0.0');
define('DEFAULT_TIMEZONE', 'America/Mexico_City');

// Configurar zona horaria
date_default_timezone_set(DEFAULT_TIMEZONE);

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'fix360_erp');
define('DB_USER', 'fix360_erp');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Configuración de sesiones
define('SESSION_NAME', 'erp_quesos_session');
define('SESSION_LIFETIME', 3600); // 1 hora

// Configuración de seguridad
define('HASH_ALGORITHM', PASSWORD_ARGON2ID);
define('CSRF_TOKEN_EXPIRY', 1800); // 30 minutos

// Configuración de archivos
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Configuración de logs
define('LOG_PATH', __DIR__ . '/../logs/');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Configuración regional (México)
if (!defined('CURRENCY_SYMBOL')) {
    define('CURRENCY_SYMBOL', '$');
}
if (!defined('CURRENCY_CODE')) {
    define('CURRENCY_CODE', 'MXN');
}
if (!defined('DATE_FORMAT')) {
    define('DATE_FORMAT', 'd/m/Y');
}
if (!defined('DATETIME_FORMAT')) {
    define('DATETIME_FORMAT', 'd/m/Y H:i:s');
}

// Rutas de controladores
define('CONTROLLERS_PATH', __DIR__ . '/../app/controllers/');
define('MODELS_PATH', __DIR__ . '/../app/models/');
define('VIEWS_PATH', __DIR__ . '/../app/views/');

// Configuración de email (para notificaciones)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM', 'noreply@erpquesos.local');

// Mostrar errores solo en desarrollo
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    define('DEBUG_MODE', true);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    define('DEBUG_MODE', false);
}
