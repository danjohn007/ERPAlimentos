<?php
/**
 * Controlador de Compras
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class ComprasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/compras/index', [
            'title' => 'Módulo de Compras',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar las compras.',
            'features' => [
                'Órdenes de compra',
                'Recepción de mercancía',
                'Control de proveedores',
                'Seguimiento de pagos'
            ]
        ]);
    }
    
    public function ordenes() {
        $this->requireAuth();
        
        $this->view->render('modules/compras/ordenes', [
            'title' => 'Órdenes de Compra',
            'message' => 'Gestión de órdenes en desarrollo.'
        ]);
    }
    
    public function recepcion() {
        $this->requireAuth();
        
        $this->view->render('modules/compras/recepcion', [
            'title' => 'Recepción de Mercancía',
            'message' => 'Control de recepción en desarrollo.'
        ]);
    }
}