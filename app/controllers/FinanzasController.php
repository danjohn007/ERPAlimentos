<?php
/**
 * Controlador de Finanzas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class FinanzasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $cuentaModel = new CuentaContable();
        $asientoModel = new AsientoContable();
        
        $balanceGeneral = $cuentaModel->getBalanceGeneral();
        $asientosRecientes = $asientoModel->getAsientosConUsuario();
        $asientosBorrador = $asientoModel->getAsientosPorEstado('borrador');
        $resumenTipos = $asientoModel->getResumenPorTipo();
        
        $this->view->render('modules/finanzas/index', [
            'title' => 'Módulo de Finanzas',
            'balance_general' => $balanceGeneral,
            'asientos_recientes' => array_slice($asientosRecientes, 0, 10),
            'asientos_borrador' => $asientosBorrador,
            'resumen_tipos' => $resumenTipos
        ]);
    }
    
    public function contabilidad() {
        $this->requireAuth();
        
        $asientoModel = new AsientoContable();
        $asientos = $asientoModel->getAsientosConUsuario();
        
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
        
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-t');
        
        $asientoModel = new AsientoContable();
        $cuentaModel = new CuentaContable();
        
        $libroDiario = $asientoModel->getLibroDiario($fechaInicio, $fechaFin);
        $balanceGeneral = $cuentaModel->getBalanceGeneral();
        $resumenPeriodo = $asientoModel->getResumenPorTipo($fechaInicio, $fechaFin);
        
        $this->view->render('modules/finanzas/reportes', [
            'title' => 'Reportes Financieros',
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'libro_diario' => $libroDiario,
            'balance_general' => $balanceGeneral,
            'resumen_periodo' => $resumenPeriodo
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