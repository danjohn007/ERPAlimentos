<?php
/**
 * Archivo de prueba de conexión y configuración
 * Test de conexión a la base de datos y verificación de URL base
 */

// Definir constante para permitir acceso
define('ERP_QUESOS', true);

// Incluir configuración
require_once 'config/config.php';
require_once 'config/database.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - ERP Quesos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-cheese"></i> Test de Conexión - ERP Quesos</h3>
                    </div>
                    <div class="card-body">
                        <h5>Verificación de Configuración</h5>
                        
                        <!-- Test de URL Base -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-link"></i> URL Base</h6>
                            <strong>URL Detectada:</strong> <?php echo BASE_URL; ?><br>
                            <strong>Servidor:</strong> <?php echo $_SERVER['HTTP_HOST']; ?><br>
                            <strong>Protocolo:</strong> <?php echo (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'HTTPS' : 'HTTP'; ?>
                        </div>

                        <!-- Test de PHP -->
                        <div class="alert alert-success">
                            <h6><i class="fab fa-php"></i> Configuración PHP</h6>
                            <strong>Versión PHP:</strong> <?php echo PHP_VERSION; ?><br>
                            <strong>Zona Horaria:</strong> <?php echo date_default_timezone_get(); ?><br>
                            <strong>Fecha/Hora Actual:</strong> <?php echo date(DATETIME_FORMAT); ?><br>
                            <strong>Modo Debug:</strong> <?php echo DEBUG_MODE ? 'Activado' : 'Desactivado'; ?>
                        </div>

                        <!-- Test de Base de Datos -->
                        <?php
                        try {
                            $db = Database::getInstance();
                            $connection = $db->getConnection();
                            
                            // Verificar versión de MySQL
                            $stmt = $connection->query("SELECT VERSION() as version");
                            $version = $stmt->fetch();
                            
                            echo '<div class="alert alert-success">';
                            echo '<h6><i class="fas fa-database"></i> Conexión a Base de Datos</h6>';
                            echo '<strong>Estado:</strong> <span class="text-success">Conectado exitosamente</span><br>';
                            echo '<strong>Host:</strong> ' . DB_HOST . '<br>';
                            echo '<strong>Base de Datos:</strong> ' . DB_NAME . '<br>';
                            echo '<strong>Versión MySQL:</strong> ' . $version['version'] . '<br>';
                            echo '<strong>Charset:</strong> ' . DB_CHARSET;
                            echo '</div>';
                            
                            // Verificar si existen las tablas
                            $stmt = $connection->query("SHOW TABLES");
                            $tables = $stmt->fetchAll();
                            
                            if (count($tables) > 0) {
                                echo '<div class="alert alert-info">';
                                echo '<h6><i class="fas fa-table"></i> Tablas Existentes</h6>';
                                echo '<strong>Total de tablas:</strong> ' . count($tables) . '<br>';
                                echo '<strong>Tablas:</strong> ';
                                $tableNames = array_map(function($table) { return array_values($table)[0]; }, $tables);
                                echo implode(', ', $tableNames);
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-warning">';
                                echo '<h6><i class="fas fa-exclamation-triangle"></i> Esquema de Base de Datos</h6>';
                                echo 'No se encontraron tablas. Es necesario ejecutar el script de instalación.';
                                echo '</div>';
                            }
                            
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<h6><i class="fas fa-times-circle"></i> Error de Conexión</h6>';
                            echo '<strong>Error:</strong> ' . $e->getMessage() . '<br>';
                            echo '<strong>Solución:</strong> Verificar las credenciales de la base de datos en config/config.php';
                            echo '</div>';
                        }
                        ?>

                        <!-- Test de Directorios -->
                        <?php
                        $directories = [
                            'config' => 'Configuración',
                            'app/controllers' => 'Controladores',
                            'app/models' => 'Modelos',
                            'app/views' => 'Vistas',
                            'public/css' => 'CSS',
                            'public/js' => 'JavaScript',
                            'uploads' => 'Archivos subidos',
                            'logs' => 'Logs'
                        ];
                        
                        $allDirsOk = true;
                        foreach ($directories as $dir => $name) {
                            if (!is_dir($dir)) {
                                $allDirsOk = false;
                                break;
                            }
                        }
                        ?>
                        
                        <div class="alert <?php echo $allDirsOk ? 'alert-success' : 'alert-warning'; ?>">
                            <h6><i class="fas fa-folder"></i> Estructura de Directorios</h6>
                            <?php if ($allDirsOk): ?>
                                <strong>Estado:</strong> <span class="text-success">Todos los directorios existen</span>
                            <?php else: ?>
                                <strong>Estado:</strong> <span class="text-warning">Algunos directorios no existen</span>
                                <ul class="mt-2 mb-0">
                                    <?php foreach ($directories as $dir => $name): ?>
                                        <li><?php echo $name; ?>: 
                                            <?php if (is_dir($dir)): ?>
                                                <span class="text-success"><i class="fas fa-check"></i> OK</span>
                                            <?php else: ?>
                                                <span class="text-danger"><i class="fas fa-times"></i> No existe</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <!-- Test de Permisos -->
                        <?php
                        $writableDirs = ['uploads', 'logs'];
                        $permissionsOk = true;
                        foreach ($writableDirs as $dir) {
                            if (!is_writable($dir)) {
                                $permissionsOk = false;
                                break;
                            }
                        }
                        ?>
                        
                        <div class="alert <?php echo $permissionsOk ? 'alert-success' : 'alert-danger'; ?>">
                            <h6><i class="fas fa-lock"></i> Permisos de Escritura</h6>
                            <?php if ($permissionsOk): ?>
                                <strong>Estado:</strong> <span class="text-success">Permisos correctos</span>
                            <?php else: ?>
                                <strong>Estado:</strong> <span class="text-danger">Permisos insuficientes</span>
                                <p class="mb-0 mt-2">
                                    <strong>Solución:</strong> Ejecutar <code>chmod 755 uploads logs</code>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="text-center mt-4">
                            <a href="install.php" class="btn btn-primary">
                                <i class="fas fa-download"></i> Instalar Base de Datos
                            </a>
                            <a href="index.php" class="btn btn-success ms-2">
                                <i class="fas fa-home"></i> Ir al Sistema
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>