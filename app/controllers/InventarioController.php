<?php
/**
 * Controlador de Inventario
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class InventarioController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $inventarioModel = new Inventario();
        
        $inventarioCompleto = $inventarioModel->getInventarioCompleto();
        $proximosVencer = $inventarioModel->getProximosVencer();
        $vencidos = $inventarioModel->getVencidos();
        $resumenTipo = $inventarioModel->getResumenPorTipo();
        
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