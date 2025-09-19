<?php
/**
 * Controlador de Finanzas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class FinanzasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/finanzas/index', [
            'title' => 'Módulo de Finanzas',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar las finanzas.',
            'features' => [
                'Contabilidad general',
                'Estados financieros',
                'Flujo de efectivo',
                'Reportes financieros'
            ]
        ]);
    }
    
    public function contabilidad() {
        $this->requireAuth();
        
        $this->view->render('modules/finanzas/contabilidad', [
            'title' => 'Contabilidad',
            'message' => 'Sistema contable en desarrollo.'
        ]);
    }
    
    public function reportes() {
        $this->requireAuth();
        
        $this->view->render('modules/finanzas/reportes', [
            'title' => 'Reportes Financieros',
            'message' => 'Reportes financieros en desarrollo.'
        ]);
    }
}