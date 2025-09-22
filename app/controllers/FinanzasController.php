<?php
/**
 * Controlador de Finanzas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class FinanzasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        // Inicializar datos vacíos
        $balanceGeneral = [];
        $asientosRecientes = [];
        $asientosBorrador = [];
        $resumenTipos = [];
        
        try {
            $cuentaModel = new CuentaContable();
            $asientoModel = new AsientoContable();
            
            $balanceGeneral = $cuentaModel->getBalanceGeneral();
            $asientosRecientes = $asientoModel->getAsientosConUsuario();
            $asientosBorrador = $asientoModel->getAsientosPorEstado('borrador');
            $resumenTipos = $asientoModel->getResumenPorTipo();
            
        } catch (Exception $e) {
            // En caso de error de base de datos, usar datos demo
            if (DEBUG_MODE) {
                $this->setFlash('warning', 'Base de datos no conectada, mostrando datos demo');
            }
            
            $balanceGeneral = [
                ['tipo' => 'Activo', 'total' => 500000.00],
                ['tipo' => 'Pasivo', 'total' => 200000.00],
                ['tipo' => 'Capital', 'total' => 300000.00],
            ];
            
            $asientosRecientes = [
                ['id' => 1, 'numero_asiento' => 'AST001', 'fecha' => date('Y-m-d'), 'concepto' => 'Asiento de apertura', 'estado' => 'confirmado'],
                ['id' => 2, 'numero_asiento' => 'AST002', 'fecha' => date('Y-m-d'), 'concepto' => 'Compra de materiales', 'estado' => 'borrador'],
            ];
            
            $asientosBorrador = [
                ['id' => 2, 'numero_asiento' => 'AST002', 'fecha' => date('Y-m-d'), 'concepto' => 'Compra de materiales'],
            ];
            
            $resumenTipos = [
                ['tipo' => 'Compra', 'total' => 25000.00],
                ['tipo' => 'Venta', 'total' => 45000.00],
                ['tipo' => 'Gasto', 'total' => 8000.00],
            ];
        }
        
        $this->view->render('modules/finanzas/index', [
            'title' => 'Módulo de Finanzas',
            'message' => 'El módulo de finanzas está en desarrollo. Pronto podrás gestionar toda la contabilidad del sistema.',
            'features' => [
                'Contabilidad general',
                'Asientos contables',
                'Balance general',
                'Estados financieros',
                'Reportes de ingresos y gastos',
                'Flujo de efectivo',
                'Cuentas por cobrar',
                'Cuentas por pagar'
            ]
        ]);
    }
    
    public function contabilidad() {
        $this->requireAuth();
        
        $asientos = [];
        
        try {
            $asientoModel = new AsientoContable();
            $asientos = $asientoModel->getAsientosConUsuario();
            
        } catch (Exception $e) {
            // En caso de error de base de datos, mostrar vista de desarrollo
            $this->view->render('modules/under-development', [
                'title' => 'Contabilidad',
                'message' => 'El módulo de contabilidad está en desarrollo.',
                'features' => [
                    'Registro de asientos contables',
                    'Libro diario y mayor',
                    'Catálogo de cuentas',
                    'Balance de comprobación',
                    'Estados financieros'
                ]
            ]);
            return;
        }
        
        $this->view->render('modules/finanzas/contabilidad', [
            'title' => 'Contabilidad',
            'asientos' => $asientos
        ]);
    }
    
    public function nuevo_asiento() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarNuevoAsiento();
            return;
        }
        
        $cuentaModel = new CuentaContable();
        $cuentasMovimiento = $cuentaModel->getCuentasMovimiento();
        
        $this->view->render('modules/finanzas/nuevo_asiento', [
            'title' => 'Nuevo Asiento Contable',
            'cuentas' => $cuentasMovimiento
        ]);
    }
    
    public function ver_asiento($id) {
        $this->requireAuth();
        
        $asientoModel = new AsientoContable();
        $asiento = $asientoModel->getById($id);
        $detalles = $asientoModel->getDetallesAsiento($id);
        
        if (!$asiento) {
            header('Location: /finanzas/contabilidad');
            exit;
        }
        
        $this->view->render('modules/finanzas/ver_asiento', [
            'title' => 'Asiento Contable #' . $asiento['numero_asiento'],
            'asiento' => $asiento,
            'detalles' => $detalles
        ]);
    }
    
    public function cuentas() {
        $this->requireAuth();
        
        $cuentaModel = new CuentaContable();
        $cuentas = $cuentaModel->getEstructuraJerarquica();
        
        $this->view->render('modules/finanzas/cuentas', [
            'title' => 'Catálogo de Cuentas',
            'cuentas' => $cuentas
        ]);
    }
    
    public function reportes() {
        $this->requireAuth();
        
        // En caso de error de base de datos, mostrar vista de desarrollo
        $this->view->render('modules/under-development', [
            'title' => 'Reportes Financieros',
            'message' => 'El módulo de reportes financieros está en desarrollo.',
            'features' => [
                'Estado de resultados',
                'Balance general',
                'Flujo de efectivo',
                'Análisis financiero',
                'Reportes personalizados'
            ]
        ]);
    }
    
    public function balance_general() {
        $this->requireAuth();
        
        $cuentaModel = new CuentaContable();
        $cuentas = $cuentaModel->getCuentasOrdenadas();
        $balanceGeneral = $cuentaModel->getBalanceGeneral();
        
        // Organizar cuentas por tipo
        $cuentasPorTipo = [];
        foreach ($cuentas as $cuenta) {
            if ($cuenta['es_movimiento'] == 1) {
                $cuentasPorTipo[$cuenta['tipo']][] = $cuenta;
            }
        }
        
        $this->view->render('modules/finanzas/balance_general', [
            'title' => 'Balance General',
            'cuentas_por_tipo' => $cuentasPorTipo,
            'balance_general' => $balanceGeneral
        ]);
    }
    
    private function procesarNuevoAsiento() {
        try {
            $asientoModel = new AsientoContable();
            
            $numeroAsiento = $asientoModel->generarNumeroAsiento();
            
            $datosAsiento = [
                'numero_asiento' => $numeroAsiento,
                'fecha' => $_POST['fecha'],
                'tipo' => $_POST['tipo'],
                'referencia' => $_POST['referencia'] ?? '',
                'concepto' => $_POST['concepto'],
                'usuario_id' => $_SESSION['user_id'],
                'estado' => 'borrador'
            ];
            
            $detalles = [];
            if (isset($_POST['detalles'])) {
                foreach ($_POST['detalles'] as $detalle) {
                    if (empty($detalle['cuenta_contable_id']) || 
                        (empty($detalle['debe']) && empty($detalle['haber']))) {
                        continue;
                    }
                    
                    $detalles[] = [
                        'cuenta_contable_id' => $detalle['cuenta_contable_id'],
                        'debe' => floatval($detalle['debe'] ?? 0),
                        'haber' => floatval($detalle['haber'] ?? 0),
                        'concepto' => $detalle['concepto'] ?? ''
                    ];
                }
            }
            
            if (empty($detalles)) {
                throw new Exception('Debe agregar al menos un detalle al asiento');
            }
            
            $asientoId = $asientoModel->crearAsientoCompleto($datosAsiento, $detalles);
            
            $_SESSION['success'] = 'Asiento contable creado exitosamente';
            header('Location: /finanzas/ver_asiento/' . $asientoId);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el asiento: ' . $e->getMessage();
            header('Location: /finanzas/nuevo_asiento');
            exit;
        }
    }
    
    public function confirmar_asiento($id) {
        $this->requireAuth();
        
        try {
            $asientoModel = new AsientoContable();
            $asientoModel->confirmarAsiento($id);
            
            $_SESSION['success'] = 'Asiento confirmado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al confirmar el asiento: ' . $e->getMessage();
        }
        
        header('Location: /finanzas/ver_asiento/' . $id);
        exit;
    }
}