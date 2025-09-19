<?php
/**
 * Controlador AJAX
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class AjaxController extends Controller {
    
    public function handle($module, $action) {
        $this->requireAuth();
        
        // Set JSON header
        header('Content-Type: application/json');
        
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
                
            default:
                $response['message'] = "Módulo AJAX '$module' no implementado aún";
        }
        
        echo json_encode($response);
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
}