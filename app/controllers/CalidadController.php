<?php
/**
 * Controlador de Control de Calidad
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class CalidadController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/calidad/index', [
            'title' => 'Módulo de Control de Calidad',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar el control de calidad.',
            'features' => [
                'Análisis de laboratorio',
                'Control de pH y humedad',
                'Certificaciones de calidad',
                'Trazabilidad completa'
            ]
        ]);
    }
    
    public function analisis() {
        $this->requireAuth();
        
        $this->view->render('modules/calidad/analisis', [
            'title' => 'Análisis de Calidad',
            'message' => 'Módulo de análisis en desarrollo.'
        ]);
    }
    
    public function trazabilidad() {
        $this->requireAuth();
        
        $this->view->render('modules/calidad/trazabilidad', [
            'title' => 'Trazabilidad',
            'message' => 'Sistema de trazabilidad en desarrollo.'
        ]);
    }
}