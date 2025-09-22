<?php
/**
 * Controlador de Materias Primas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class MateriasPrimasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        // Inicializar datos vacíos
        $materiasPrimas = [];
        $stockBajo = [];
        $proximasVencer = [];
        $proveedoresCertificados = [];
        
        try {
            $materiaPrimaModel = new MateriaPrima();
            $proveedorModel = new Proveedor();
            
            $materiasPrimas = $materiaPrimaModel->getConProveedor();
            $stockBajo = $materiaPrimaModel->getStockBajo();
            $proximasVencer = $materiaPrimaModel->getProximasVencer();
            $proveedoresCertificados = $proveedorModel->getCertificados();
            
        } catch (Exception $e) {
            // En caso de error de base de datos, usar datos demo
            if (DEBUG_MODE) {
                $this->setFlash('warning', 'Base de datos no conectada, mostrando datos demo');
            }
            
            $materiasPrimas = [
                [
                    'id' => 1, 'codigo' => 'MP001', 'nombre' => 'Leche Entera', 'tipo' => 'Lácteo',
                    'stock_actual' => 500, 'unidad_medida' => 'litros', 'stock_minimo' => 100, 
                    'costo_unitario' => 3.50, 'proveedor_nombre' => 'Lácteos San Pedro',
                    'estado' => 'activo', 'requiere_refrigeracion' => 1, 'certificaciones' => 'ISO 22000'
                ],
                [
                    'id' => 2, 'codigo' => 'MP002', 'nombre' => 'Cuajo Natural', 'tipo' => 'Insumo',
                    'stock_actual' => 15, 'unidad_medida' => 'kg', 'stock_minimo' => 20, 
                    'costo_unitario' => 45.00, 'proveedor_nombre' => 'Insumos Queseros',
                    'estado' => 'activo', 'requiere_refrigeracion' => 0, 'certificaciones' => 'HACCP'
                ],
                [
                    'id' => 3, 'codigo' => 'MP003', 'nombre' => 'Sal Marina', 'tipo' => 'Condimento',
                    'stock_actual' => 80, 'unidad_medida' => 'kg', 'stock_minimo' => 25, 
                    'costo_unitario' => 12.00, 'proveedor_nombre' => 'Distribuidora Sal',
                    'estado' => 'activo', 'requiere_refrigeracion' => 0, 'certificaciones' => null
                ],
            ];
            
            $stockBajo = [
                ['nombre' => 'Cuajo Natural', 'stock_actual' => 15, 'stock_minimo' => 20, 'unidad_medida' => 'kg']
            ];
            
            $proximasVencer = [
                ['nombre' => 'Leche Entera', 'cantidad' => 50, 'fecha_caducidad' => date('Y-m-d', strtotime('+2 days'))],
            ];
            
            $proveedoresCertificados = [
                ['nombre' => 'Lácteos San Pedro', 'certificacion' => 'ISO 22000'],
                ['nombre' => 'Insumos Queseros', 'certificacion' => 'HACCP'],
            ];
        }
        
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
        
        $proveedores = [];
        
        try {
            $proveedorModel = new Proveedor();
            $proveedores = $proveedorModel->getActivos();
            
        } catch (Exception $e) {
            // En caso de error de base de datos, usar datos demo
            if (DEBUG_MODE) {
                $this->setFlash('warning', 'Base de datos no conectada, mostrando datos demo');
            }
            
            $proveedores = [
                ['id' => 1, 'nombre' => 'Lácteos San Pedro', 'telefono' => '555-0101', 'email' => 'ventas@lacteossanpedro.com', 'estado' => 'activo'],
                ['id' => 2, 'nombre' => 'Insumos Queseros SA', 'telefono' => '555-0202', 'email' => 'contacto@insumosqueseros.com', 'estado' => 'activo'],
                ['id' => 3, 'nombre' => 'Distribuidora Sal', 'telefono' => '555-0303', 'email' => 'pedidos@distsal.com', 'estado' => 'activo'],
            ];
        }
        
        $this->view->render('modules/materias-primas/proveedores', [
            'title' => 'Gestión de Proveedores',
            'proveedores' => $proveedores
        ]);
    }
    
    public function inventario() {
        $this->requireAuth();
        
        $inventario = [];
        $resumen = [];
        
        try {
            $materiaPrimaModel = new MateriaPrima();
            $inventarioModel = new Inventario();
            
            $inventario = $inventarioModel->getInventarioPorUbicacion();
            $resumen = $inventarioModel->getResumenPorTipo();
            
        } catch (Exception $e) {
            // En caso de error de base de datos, mostrar vista de desarrollo
            $this->view->render('modules/under-development', [
                'title' => 'Inventario de Materias Primas',
                'message' => 'El módulo de inventario de materias primas está en desarrollo.',
                'features' => [
                    'Control de stock por ubicación',
                    'Alertas de stock mínimo',
                    'Seguimiento de lotes',
                    'Reportes de movimientos',
                    'Integración con compras'
                ]
            ]);
            return;
        }
        
        $this->view->render('modules/materias-primas/inventario', [
            'title' => 'Inventario de Materias Primas',
            'inventario' => $inventario,
            'resumen' => $resumen
        ]);
    }
}