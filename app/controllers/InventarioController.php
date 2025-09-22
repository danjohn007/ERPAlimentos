<?php
/**
 * Controlador de Inventario
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class InventarioController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        // Inicializar datos vacíos
        $inventarioCompleto = [];
        $proximosVencer = [];
        $vencidos = [];
        $resumenTipo = [];
        
        try {
            $inventarioModel = new Inventario();
            
            $inventarioCompleto = $inventarioModel->getInventarioCompleto();
            $proximosVencer = $inventarioModel->getProximosVencer();
            $vencidos = $inventarioModel->getVencidos();
            $resumenTipo = $inventarioModel->getResumenPorTipo();
            
        } catch (Exception $e) {
            // En caso de error de base de datos, usar datos demo
            if (DEBUG_MODE) {
                $this->setFlash('warning', 'Base de datos no conectada, mostrando datos demo');
            }
            
            $inventarioCompleto = [
                ['id' => 1, 'producto' => 'Queso Manchego', 'cantidad' => 150, 'unidad' => 'unidades', 'valor_unitario' => 25.50, 'fecha_vencimiento' => '2024-12-01'],
                ['id' => 2, 'producto' => 'Queso Fresco', 'cantidad' => 80, 'unidad' => 'kg', 'valor_unitario' => 18.00, 'fecha_vencimiento' => '2024-10-15'],
                ['id' => 3, 'producto' => 'Yogurt Natural', 'cantidad' => 200, 'unidad' => 'litros', 'valor_unitario' => 12.00, 'fecha_vencimiento' => '2024-10-10'],
            ];
            
            $proximosVencer = [
                ['producto' => 'Yogurt Natural', 'cantidad' => 200, 'fecha_vencimiento' => '2024-10-10', 'dias_restantes' => 3],
                ['producto' => 'Queso Fresco', 'cantidad' => 80, 'fecha_vencimiento' => '2024-10-15', 'dias_restantes' => 8],
            ];
            
            $vencidos = [];
            
            $resumenTipo = [
                ['tipo' => 'Lácteos', 'cantidad_productos' => 3, 'valor_total' => 6840.00],
                ['tipo' => 'Procesados', 'cantidad_productos' => 1, 'valor_total' => 2400.00],
            ];
        }
        
        $this->view->render('modules/inventario/index', [
            'title' => 'Módulo de Inventario',
            'inventario_completo' => $inventarioCompleto,
            'proximos_vencer' => $proximosVencer,
            'vencidos' => $vencidos,
            'resumen_tipo' => $resumenTipo
        ]);
    }
    
    public function productos() {
        $this->requireAuth();
        
        $this->view->render('modules/under-development', [
            'title' => 'Productos en Inventario',
            'message' => 'El módulo de productos está en desarrollo.',
            'features' => [
                'Catálogo de productos',
                'Control de stock',
                'Precios y costos',
                'Categorización',
                'Códigos de barras'
            ]
        ]);
    }
    
    public function movimientos() {
        $this->requireAuth();
        
        $this->view->render('modules/under-development', [
            'title' => 'Movimientos de Inventario',
            'message' => 'El módulo de movimientos está en desarrollo.',
            'features' => [
                'Entradas de inventario',
                'Salidas de inventario',
                'Transferencias',
                'Ajustes de inventario',
                'Historial de movimientos'
            ]
        ]);
    }
}