<?php
/**
 * Script de instalación de la base de datos
 */

// Definir constante para permitir acceso
define('ERP_QUESOS', true);

// Incluir configuración
require_once 'config/config.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación - ERP Quesos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0"><i class="fas fa-download"></i> Instalación ERP Quesos</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
                            // Realizar instalación
                            try {
                                // Leer el archivo SQL
                                $sql = file_get_contents('install.sql');
                                
                                if ($sql === false) {
                                    throw new Exception('No se pudo leer el archivo install.sql');
                                }
                                
                                // Crear conexión sin especificar base de datos
                                $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
                                $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                    PDO::ATTR_EMULATE_PREPARES => false
                                ]);
                                
                                // Ejecutar el script SQL
                                $statements = explode(';', $sql);
                                $executed = 0;
                                $errors = [];
                                
                                foreach ($statements as $statement) {
                                    $statement = trim($statement);
                                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                                        try {
                                            $pdo->exec($statement);
                                            $executed++;
                                        } catch (PDOException $e) {
                                            $errors[] = $e->getMessage();
                                        }
                                    }
                                }
                                
                                if (empty($errors)) {
                                    echo '<div class="alert alert-success">';
                                    echo '<h5><i class="fas fa-check-circle"></i> Instalación Exitosa</h5>';
                                    echo '<p>La base de datos se ha instalado correctamente.</p>';
                                    echo '<ul>';
                                    echo '<li><strong>Sentencias ejecutadas:</strong> ' . $executed . '</li>';
                                    echo '<li><strong>Base de datos:</strong> ' . DB_NAME . '</li>';
                                    echo '<li><strong>Usuario administrador:</strong> admin</li>';
                                    echo '<li><strong>Contraseña temporal:</strong> admin123</li>';
                                    echo '</ul>';
                                    echo '<div class="alert alert-warning mt-3">';
                                    echo '<strong>¡Importante!</strong> Cambie la contraseña del administrador después del primer acceso.';
                                    echo '</div>';
                                    echo '</div>';
                                    
                                    echo '<div class="text-center mt-4">';
                                    echo '<a href="index.php" class="btn btn-primary btn-lg">';
                                    echo '<i class="fas fa-home"></i> Acceder al Sistema';
                                    echo '</a>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="alert alert-danger">';
                                    echo '<h5><i class="fas fa-exclamation-triangle"></i> Errores Durante la Instalación</h5>';
                                    echo '<p>Se ejecutaron ' . $executed . ' sentencias, pero ocurrieron algunos errores:</p>';
                                    echo '<ul>';
                                    foreach ($errors as $error) {
                                        echo '<li>' . htmlspecialchars($error) . '</li>';
                                    }
                                    echo '</ul>';
                                    echo '</div>';
                                }
                                
                            } catch (Exception $e) {
                                echo '<div class="alert alert-danger">';
                                echo '<h5><i class="fas fa-times-circle"></i> Error de Instalación</h5>';
                                echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
                                echo '<p><strong>Solución:</strong></p>';
                                echo '<ul>';
                                echo '<li>Verifique que MySQL esté ejecutándose</li>';
                                echo '<li>Verifique las credenciales en config/config.php</li>';
                                echo '<li>Verifique que el usuario tenga permisos para crear bases de datos</li>';
                                echo '</ul>';
                                echo '</div>';
                            }
                        } else {
                            // Mostrar formulario de instalación
                            ?>
                            <h5>Bienvenido al Instalador de ERP Quesos</h5>
                            <p>Este script creará la base de datos y las tablas necesarias para el funcionamiento del sistema.</p>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Configuración Actual</h6>
                                <ul class="mb-0">
                                    <li><strong>Servidor:</strong> <?php echo DB_HOST; ?></li>
                                    <li><strong>Base de Datos:</strong> <?php echo DB_NAME; ?></li>
                                    <li><strong>Usuario:</strong> <?php echo DB_USER; ?></li>
                                    <li><strong>Charset:</strong> <?php echo DB_CHARSET; ?></li>
                                </ul>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle"></i> Antes de Continuar</h6>
                                <ul class="mb-0">
                                    <li>Asegúrese de que MySQL esté ejecutándose</li>
                                    <li>Verifique las credenciales en <code>config/config.php</code></li>
                                    <li>El usuario debe tener permisos para crear bases de datos</li>
                                    <li>Si la base de datos ya existe, será recreada (se perderán los datos)</li>
                                </ul>
                            </div>
                            
                            <h6>Datos de Ejemplo Incluidos:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul>
                                        <li>3 usuarios de prueba</li>
                                        <li>3 proveedores</li>
                                        <li>3 clientes</li>
                                        <li>4 productos</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li>4 materias primas</li>
                                        <li>3 recetas</li>
                                        <li>3 lotes de producción</li>
                                        <li>Inventario inicial</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <form method="POST" class="mt-4">
                                <div class="text-center">
                                    <button type="submit" name="install" class="btn btn-success btn-lg">
                                        <i class="fas fa-play"></i> Instalar Base de Datos
                                    </button>
                                </div>
                            </form>
                            
                            <div class="text-center mt-3">
                                <a href="test_connection.php" class="btn btn-outline-primary">
                                    <i class="fas fa-check"></i> Probar Conexión
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>