<?php
/**
 * Controlador de Reportes
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class ReportesController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/reportes/index', [
            'title' => 'Módulo de Reportes',
            'message' => 'Este módulo está en desarrollo. Pronto podrás generar reportes.',
            'features' => [
                'Reportes de producción',
                'Análisis de ventas',
                'Reportes financieros',
                'Business Intelligence'
            ]
        ]);
    }
    
    public function businessIntelligence() {
        $this->requireAuth();
        
        $this->view->render('modules/reportes/bi', [
            'title' => 'Business Intelligence',
            'message' => 'Dashboard de BI en desarrollo.'
        ]);
    }
}