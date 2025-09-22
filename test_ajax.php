<?php
/**
 * Archivo de prueba para AJAX
 */

// Definir constante para permitir acceso
define('ERP_QUESOS', true);

// Configurar sesión
session_name('erp_quesos_session');
session_start();

// Incluir archivos de configuración
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'app/core/Auth.php';

// Verificar autenticación
if (!Auth::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

// Set JSON header
header('Content-Type: application/json');

// Verificar el endpoint solicitado
$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
    case 'profile':
        try {
            $db = Database::getInstance();
            $userId = Auth::getUserId();
            
            $sql = "SELECT id, username, email, nombre, apellidos, rol, ultimo_acceso, fecha_creacion 
                    FROM usuarios WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Formatear fechas
                if ($user['ultimo_acceso']) {
                    $user['ultimo_acceso'] = date('d/m/Y H:i:s', strtotime($user['ultimo_acceso']));
                } else {
                    $user['ultimo_acceso'] = 'Nunca';
                }
                if ($user['fecha_creacion']) {
                    $user['fecha_creacion'] = date('d/m/Y', strtotime($user['fecha_creacion']));
                } else {
                    $user['fecha_creacion'] = 'N/A';
                }
                
                echo json_encode([
                    'success' => true,
                    'user' => $user
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        break;
        
    case 'change_password':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
            exit;
        }
        
        if (strlen($newPassword) < 8) {
            echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 8 caracteres']);
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
            exit;
        }
        
        $userId = Auth::getUserId();
        $result = Auth::changePassword($userId, $currentPassword, $newPassword);
        echo json_encode($result);
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint no válido',
            'available_endpoints' => ['profile', 'change_password']
        ]);
}
?>