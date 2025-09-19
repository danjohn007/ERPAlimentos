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
                
            case 'materias-primas':
                $response = $this->handleMateriasPrimas($action);
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