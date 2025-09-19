<?php
/**
 * Controlador de Ventas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class VentasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/ventas/index', [
            'title' => 'Módulo de Ventas',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar las ventas.',
            'features' => [
                'Gestión de clientes',
                'Órdenes de venta',
                'Facturación electrónica',
                'Seguimiento de cobranza'
            ]
        ]);
    }
    
    public function clientes() {
        $this->requireAuth();
        
        $this->view->render('modules/ventas/clientes', [
            'title' => 'Gestión de Clientes',
            'message' => 'Módulo de clientes en desarrollo.'
        ]);
    }
    
    public function ordenes() {
        $this->requireAuth();
        
        $this->view->render('modules/ventas/ordenes', [
            'title' => 'Órdenes de Venta',
            'message' => 'Gestión de órdenes en desarrollo.'
        ]);
    }
    
    public function facturacion() {
        $this->requireAuth();
        
        $this->view->render('modules/ventas/facturacion', [
            'title' => 'Facturación',
            'message' => 'Sistema de facturación en desarrollo.'
        ]);
    }
}