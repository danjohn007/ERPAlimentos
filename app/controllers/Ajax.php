<?php
/**
 * Controlador AJAX (sin sufijo Controller para coincidir con el router)
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Ajax extends Controller {
    
    public function handle($module, $action = null) {
        $this->requireAuth();
        
        // Set JSON header
        header('Content-Type: application/json');
        
        // Manejar endpoints específicos de usuario
        if ($module === 'get_user_profile') {
            $this->get_user_profile();
            return;
        }
        
        if ($module === 'change_password') {
            $this->change_password();
            return;
        }
        
        // Basic response for all AJAX requests
        $response = [
            'success' => false,
            'message' => 'Funcionalidad AJAX en desarrollo',
            'module' => $module,
            'action' => $action
        ];
        
        // Handle specific AJAX endpoints when implemented
        switch ($module) {
            case 'dashboard':
                $response = $this->handleDashboard($action);
                break;
                
            case 'produccion':
                $response = $this->handleProduccion($action);
                break;
                
            case 'materias-primas':
                $response = $this->handleMateriasPrimas($action);
                break;
                
            default:
                $response['message'] = "Módulo AJAX '$module' no implementado aún";
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Obtener datos del perfil del usuario actual
     */
    public function get_user_profile() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        try {
            $db = Database::getInstance();
            $userId = Auth::getUserId();
            
            if (!$userId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ]);
                exit;
            }
            
            $sql = "SELECT id, username, email, nombre, apellidos, rol, ultimo_acceso, fecha_creacion 
                    FROM usuarios WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Formatear fechas para mejor legibilidad
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
                    'user' => $user,
                    'debug' => 'Datos obtenidos correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no encontrado en la base de datos',
                    'debug' => 'UserID: ' . $userId
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener datos del usuario: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
        exit;
    }
    
    /**
     * Cambiar contraseña del usuario actual
     */
    public function change_password() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validaciones
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son requeridos'
                ]);
                exit;
            }
            
            if (strlen($newPassword) < 8) {
                echo json_encode([
                    'success' => false,
                    'message' => 'La nueva contraseña debe tener al menos 8 caracteres'
                ]);
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Las contraseñas no coinciden'
                ]);
                exit;
            }
            
            // Usar el método de Auth para cambiar la contraseña
            $userId = Auth::getUserId();
            $result = Auth::changePassword($userId, $currentPassword, $newPassword);
            
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    private function handleDashboard($action) {
        switch ($action) {
            case 'stats':
                return [
                    'success' => true,
                    'data' => [
                        'message' => 'Estadísticas del dashboard disponibles en modo demo'
                    ]
                ];
            default:
                return [
                    'success' => false,
                    'message' => "Acción '$action' no implementada para dashboard"
                ];
        }
    }
    
    private function handleProduccion($action) {
        return [
            'success' => false,
            'message' => "Funcionalidad de producción '$action' en desarrollo"
        ];
    }
    
    private function handleMateriasPrimas($action) {
        switch ($action) {
            case 'check-raw-materials':
                return $this->checkRawMaterials();
            case 'inventory-adjustment':
                return $this->processInventoryAdjustment();
            default:
                return [
                    'success' => false,
                    'message' => "Acción '$action' no implementada para materias primas"
                ];
        }
    }
    
    private function checkRawMaterials() {
        try {
            $materiaPrimaModel = new MateriaPrima();
            $stockBajo = $materiaPrimaModel->getStockBajo();
            
            // Filter critical low stock (less than 50% of minimum)
            $criticalLow = array_filter($stockBajo, function($mp) {
                return $mp['stock_actual'] <= ($mp['stock_minimo'] * 0.5);
            });
            
            return [
                'success' => true,
                'data' => [
                    'critical_low' => array_values($criticalLow),
                    'total_low' => count($stockBajo),
                    'total_critical' => count($criticalLow)
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al verificar materias primas: ' . $e->getMessage()
            ];
        }
    }
    
    private function processInventoryAdjustment() {
        // This would handle inventory adjustments
        // For now, return a placeholder response
        return [
            'success' => false,
            'message' => 'Funcionalidad de ajuste de inventario en desarrollo'
        ];
    }
}