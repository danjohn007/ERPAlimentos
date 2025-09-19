<?php
/**
 * Controlador de Ventas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class VentasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $ordenModel = new OrdenVenta();
        $clienteModel = new Cliente();
        
        $ordenesPendientes = $ordenModel->getByEstado('pendiente');
        $ordenesProceso = $ordenModel->getByEstado('proceso');
        $clientesActivos = $clienteModel->getActivos();
        
        $this->view->render('modules/ventas/index', [
            'title' => 'Módulo de Ventas',
            'ordenes_pendientes' => $ordenesPendientes,
            'ordenes_proceso' => $ordenesProceso,
            'clientes_activos' => $clientesActivos
        ]);
    }
    
    public function clientes() {
        $this->requireAuth();
        
        $clienteModel = new Cliente();
        $clientes = $clienteModel->getActivos();
        
        $this->view->render('modules/ventas/clientes', [
            'title' => 'Gestión de Clientes',
            'clientes' => $clientes
        ]);
    }
    
    public function ordenes() {
        $this->requireAuth();
        
        $ordenModel = new OrdenVenta();
        $ordenes = $ordenModel->getOrdenesConCliente();
        
        $this->view->render('modules/ventas/ordenes', [
            'title' => 'Órdenes de Venta',
            'ordenes' => $ordenes
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