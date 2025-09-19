<?php
/**
 * Controlador de Recursos Humanos
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class RecursosHumanosController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/rrhh/index', [
            'title' => 'Módulo de Recursos Humanos',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar recursos humanos.',
            'features' => [
                'Gestión de empleados',
                'Control de asistencia',
                'Nómina y pagos',
                'Evaluaciones de desempeño'
            ]
        ]);
    }
    
    public function empleados() {
        $this->requireAuth();
        
        $this->view->render('modules/rrhh/empleados', [
            'title' => 'Gestión de Empleados',
            'message' => 'Módulo de empleados en desarrollo.'
        ]);
    }
    
    public function nomina() {
        $this->requireAuth();
        
        $this->view->render('modules/rrhh/nomina', [
            'title' => 'Nómina',
            'message' => 'Sistema de nómina en desarrollo.'
        ]);
    }
}