<?php
/**
 * Controlador de Materias Primas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class MateriasPrimasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $materiaPrimaModel = new MateriaPrima();
        $proveedorModel = new Proveedor();
        
        $materiasPrimas = $materiaPrimaModel->getConProveedor();
        $stockBajo = $materiaPrimaModel->getStockBajo();
        $proximasVencer = $materiaPrimaModel->getProximasVencer();
        $proveedoresCertificados = $proveedorModel->getCertificados();
        
        $this->view->render('modules/materias-primas/index', [
            'title' => 'Módulo de Materias Primas',
            'materias_primas' => $materiasPrimas,
            'stock_bajo' => $stockBajo,
            'proximas_vencer' => $proximasVencer,
            'proveedores_certificados' => $proveedoresCertificados
        ]);
    }
    
    public function proveedores() {
        $this->requireAuth();
        
        $proveedorModel = new Proveedor();
        $proveedores = $proveedorModel->getActivos();
        
        $this->view->render('modules/materias-primas/proveedores', [
            'title' => 'Gestión de Proveedores',
            'proveedores' => $proveedores
        ]);
    }
    
    public function inventario() {
        $this->requireAuth();
        
        $materiaPrimaModel = new MateriaPrima();
        $inventarioModel = new Inventario();
        
        $inventario = $inventarioModel->getInventarioPorUbicacion();
        $resumen = $inventarioModel->getResumenPorTipo();
        
        $this->view->render('modules/materias-primas/inventario', [
            'title' => 'Inventario de Materias Primas',
            'inventario' => $inventario,
            'resumen' => $resumen
        ]);
    }
}