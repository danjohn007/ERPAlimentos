<?php
/**
 * Controlador de Compras
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class ComprasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $ordenCompraModel = new OrdenCompra();
        $proveedorModel = new Proveedor();
        
        $ordenesPendientes = $ordenCompraModel->getByEstado('borrador');
        $ordenesEnProceso = $ordenCompraModel->getByEstado('enviado');
        $ordenesPorRecibir = $ordenCompraModel->getOrdenesPendientesRecepcion();
        $proveedoresActivos = $proveedorModel->getActivos();
        
        $this->view->render('modules/compras/index', [
            'title' => 'Módulo de Compras',
            'ordenes_pendientes' => $ordenesPendientes,
            'ordenes_en_proceso' => $ordenesEnProceso,
            'ordenes_por_recibir' => $ordenesPorRecibir,
            'proveedores_activos' => $proveedoresActivos
        ]);
    }
    
    public function ordenes() {
        $this->requireAuth();
        
        $ordenCompraModel = new OrdenCompra();
        $ordenes = $ordenCompraModel->getOrdenesConProveedor();
        
        $this->view->render('modules/compras/ordenes', [
            'title' => 'Órdenes de Compra',
            'ordenes' => $ordenes
        ]);
    }
    
    public function nueva_orden() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarNuevaOrden();
            return;
        }
        
        $proveedorModel = new Proveedor();
        $materiaPrimaModel = new MateriaPrima();
        
        $proveedores = $proveedorModel->getActivos();
        $materiasPrimas = $materiaPrimaModel->getActivos();
        
        $this->view->render('modules/compras/nueva_orden', [
            'title' => 'Nueva Orden de Compra',
            'proveedores' => $proveedores,
            'materias_primas' => $materiasPrimas
        ]);
    }
    
    public function ver_orden($id) {
        $this->requireAuth();
        
        $ordenCompraModel = new OrdenCompra();
        $orden = $ordenCompraModel->getOrdenCompleta($id);
        $detalles = $ordenCompraModel->getDetallesOrden($id);
        
        if (!$orden) {
            header('Location: /compras/ordenes');
            exit;
        }
        
        $this->view->render('modules/compras/ver_orden', [
            'title' => 'Orden de Compra #' . $orden['numero_orden'],
            'orden' => $orden,
            'detalles' => $detalles
        ]);
    }
    
    public function recepcion() {
        $this->requireAuth();
        
        $ordenCompraModel = new OrdenCompra();
        $ordenesPorRecibir = $ordenCompraModel->getOrdenesPendientesRecepcion();
        
        $this->view->render('modules/compras/recepcion', [
            'title' => 'Recepción de Mercancía',
            'ordenes_por_recibir' => $ordenesPorRecibir
        ]);
    }
    
    public function proveedores() {
        $this->requireAuth();
        
        $proveedorModel = new Proveedor();
        $proveedores = $proveedorModel->getActivos();
        
        $this->view->render('modules/compras/proveedores', [
            'title' => 'Gestión de Proveedores',
            'proveedores' => $proveedores
        ]);
    }
    
    private function procesarNuevaOrden() {
        try {
            $ordenCompraModel = new OrdenCompra();
            
            $numeroOrden = $ordenCompraModel->generarNumeroOrden();
            
            $datosOrden = [
                'numero_orden' => $numeroOrden,
                'proveedor_id' => $_POST['proveedor_id'],
                'fecha_orden' => date('Y-m-d H:i:s'),
                'fecha_entrega_esperada' => $_POST['fecha_entrega_esperada'],
                'observaciones' => $_POST['observaciones'] ?? '',
                'comprador_id' => $_SESSION['user_id'],
                'estado' => 'borrador'
            ];
            
            $ordenId = $ordenCompraModel->insert($datosOrden);
            
            if ($ordenId && isset($_POST['detalles'])) {
                $this->procesarDetallesOrden($ordenId, $_POST['detalles']);
                $totales = $ordenCompraModel->calcularTotalDetalles($ordenId);
                
                $ordenCompraModel->update($ordenId, [
                    'subtotal' => $totales['subtotal'],
                    'impuestos' => $totales['impuestos'],
                    'total' => $totales['total'],
                    'saldo_pendiente' => $totales['total']
                ]);
            }
            
            $_SESSION['success'] = 'Orden de compra creada exitosamente';
            header('Location: /compras/ver_orden/' . $ordenId);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear la orden: ' . $e->getMessage();
            header('Location: /compras/nueva_orden');
            exit;
        }
    }
    
    private function procesarDetallesOrden($ordenId, $detalles) {
        foreach ($detalles as $detalle) {
            if (empty($detalle['materia_prima_id']) || empty($detalle['cantidad'])) {
                continue;
            }
            
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
            $descuento = $detalle['descuento'] ?? 0;
            $subtotalFinal = $subtotal - $descuento;
            
            $datosDetalle = [
                'orden_compra_id' => $ordenId,
                'materia_prima_id' => $detalle['materia_prima_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descuento' => $descuento,
                'subtotal' => $subtotalFinal,
                'observaciones' => $detalle['observaciones'] ?? ''
            ];
            
            $this->db->query(
                "INSERT INTO orden_compra_detalles (orden_compra_id, materia_prima_id, cantidad, precio_unitario, descuento, subtotal, observaciones) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                array_values($datosDetalle)
            );
        }
    }
}