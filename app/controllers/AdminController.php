<?php
/**
 * Controlador de Administración
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class AdminController extends Controller {
    
    public function index() {
        $this->requireRole(['admin']);
        
        $this->view->render('modules/admin/index', [
            'title' => 'Módulo de Administración',
            'message' => 'Este módulo está en desarrollo. Pronto podrás administrar el sistema.',
            'features' => [
                'Gestión de usuarios',
                'Configuración del sistema',
                'Respaldos de base de datos',
                'Logs del sistema'
            ]
        ]);
    }
    
    public function usuarios() {
        $this->requireRole(['admin']);
        
        $this->view->render('modules/admin/usuarios', [
            'title' => 'Gestión de Usuarios',
            'message' => 'Módulo de usuarios en desarrollo.'
        ]);
    }
    
    public function configuracion() {
        $this->requireRole(['admin']);
        
        $this->view->render('modules/admin/configuracion', [
            'title' => 'Configuración del Sistema',
            'message' => 'Panel de configuración en desarrollo.'
        ]);
    }
}