<?php
/**
 * Controlador de Inventario
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class InventarioController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/inventario/index', [
            'title' => 'Módulo de Inventario',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar el inventario.',
            'features' => [
                'Control de stock',
                'Movimientos de inventario',
                'Alertas de stock mínimo',
                'Valoración de inventario'
            ]
        ]);
    }
    
    public function productos() {
        $this->requireAuth();
        
        $this->view->render('modules/inventario/productos', [
            'title' => 'Productos en Inventario',
            'message' => 'Gestión de productos en desarrollo.'
        ]);
    }
    
    public function movimientos() {
        $this->requireAuth();
        
        $this->view->render('modules/inventario/movimientos', [
            'title' => 'Movimientos de Inventario',
            'message' => 'Control de movimientos en desarrollo.'
        ]);
    }
}