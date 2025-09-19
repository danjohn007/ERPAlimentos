<?php
/**
 * Controlador de Producción
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class ProduccionController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $this->view->render('modules/produccion/index', [
            'title' => 'Módulo de Producción',
            'message' => 'Este módulo está en desarrollo. Pronto podrás gestionar la producción de quesos.',
            'features' => [
                'Gestión de lotes de producción',
                'Control de recetas',
                'Seguimiento de procesos',
                'Programación de producción'
            ]
        ]);
    }
    
    public function recetas() {
        $this->requireAuth();
        
        $this->view->render('modules/produccion/recetas', [
            'title' => 'Recetas de Producción',
            'message' => 'Gestión de recetas en desarrollo.'
        ]);
    }
    
    public function lotes() {
        $this->requireAuth();
        
        $this->view->render('modules/produccion/lotes', [
            'title' => 'Lotes de Producción',
            'message' => 'Gestión de lotes en desarrollo.'
        ]);
    }
    
    public function crearLote() {
        $this->requireAuth();
        
        $this->view->render('modules/produccion/crear-lote', [
            'title' => 'Crear Lote de Producción',
            'message' => 'Funcionalidad en desarrollo.'
        ]);
    }
    
    public function verLote($id) {
        $this->requireAuth();
        
        $this->view->render('modules/produccion/ver-lote', [
            'title' => 'Ver Lote de Producción',
            'message' => 'Funcionalidad en desarrollo.',
            'lote_id' => $id
        ]);
    }
}