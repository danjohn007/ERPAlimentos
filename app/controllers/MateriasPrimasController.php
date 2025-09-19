<?php
/**
 * Controlador de Materias Primas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class MateriasPrimasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/materias-primas/index', [
            'title' => 'Módulo de Materias Primas',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar materias primas.',
            'features' => [
                'Inventario de materias primas',
                'Gestión de proveedores',
                'Control de calidad',
                'Seguimiento de caducidades'
            ]
        ]);
    }
    
    public function proveedores() {
        $this->requireAuth();
        
        $this->view->render('modules/materias-primas/proveedores', [
            'title' => 'Proveedores',
            'message' => 'Gestión de proveedores en desarrollo.'
        ]);
    }
    
    public function inventario() {
        $this->requireAuth();
        
        $this->view->render('modules/materias-primas/inventario', [
            'title' => 'Inventario de Materias Primas',
            'message' => 'Control de inventario en desarrollo.'
        ]);
    }
}