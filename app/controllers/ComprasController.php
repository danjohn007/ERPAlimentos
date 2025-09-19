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
    
    public function inventario_materias_primas() {
        $this->requireAuth();
        
        $materiaPrimaModel = new MateriaPrima();
        $inventario = $materiaPrimaModel->getInventarioCompleto();
        
        $this->view->render('modules/compras/inventario_materias', [
            'title' => 'Inventario de Materias Primas',
            'inventario' => $inventario
        ]);
    }
    
    public function reportes() {
        $this->requireAuth();
        
        $ordenCompraModel = new OrdenCompra();
        $proveedorModel = new Proveedor();
        
        // Estadísticas del mes actual
        $mesActual = date('Y-m');
        $comprasMes = $ordenCompraModel->query(
            "SELECT COUNT(*) as total_ordenes, SUM(total) as total_compras
             FROM ordenes_compra 
             WHERE DATE_FORMAT(fecha_orden, '%Y-%m') = ?",
            [$mesActual]
        );
        
        // Top proveedores
        $topProveedores = $ordenCompraModel->query(
            "SELECT p.nombre, COUNT(oc.id) as total_ordenes, SUM(oc.total) as total_compras
             FROM ordenes_compra oc
             INNER JOIN proveedores p ON oc.proveedor_id = p.id
             WHERE oc.estado != 'cancelado'
             GROUP BY p.id, p.nombre
             ORDER BY total_compras DESC
             LIMIT 5"
        );
        
        // Órdenes por estado
        $ordenesPorEstado = $ordenCompraModel->query(
            "SELECT estado, COUNT(*) as total
             FROM ordenes_compra
             GROUP BY estado"
        );
        
        $this->view->render('modules/compras/reportes', [
            'title' => 'Reportes de Compras',
            'compras_mes' => $comprasMes[0] ?? ['total_ordenes' => 0, 'total_compras' => 0],
            'top_proveedores' => $topProveedores,
            'ordenes_por_estado' => $ordenesPorEstado
        ]);
    }
    
    public function nuevo_proveedor() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarProveedor();
            return;
        }
        
        $this->view->render('modules/compras/nuevo_proveedor', [
            'title' => 'Nuevo Proveedor'
        ]);
    }
    
    private function procesarProveedor($id = null) {
        try {
            $proveedorModel = new Proveedor();
            
            $datos = [
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'contacto' => $_POST['contacto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'rfc' => $_POST['rfc'] ?? '',
                'terminos_pago' => $_POST['terminos_pago'] ?? 'contado',
                'dias_credito' => intval($_POST['dias_credito'] ?? 0),
                'estado' => 'activo'
            ];
            
            if ($id) {
                // Actualizar proveedor existente
                $proveedorModel->update($id, $datos);
                $_SESSION['success'] = 'Proveedor actualizado exitosamente';
                header('Location: /compras/proveedores');
            } else {
                // Crear nuevo proveedor
                $proveedorId = $proveedorModel->insert($datos);
                $_SESSION['success'] = 'Proveedor creado exitosamente';
                header('Location: /compras/proveedores');
            }
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al procesar el proveedor: ' . $e->getMessage();
            if ($id) {
                header('Location: /compras/editar_proveedor/' . $id);
            } else {
                header('Location: /compras/nuevo_proveedor');
            }
            exit;
        }
    }
    
    public function alertas_inventario() {
        $this->requireAuth();
        
        header('Content-Type: application/json');
        
        try {
            $materiaPrimaModel = new MateriaPrima();
            
            // Obtener materias primas con stock bajo
            $alertas = $materiaPrimaModel->query(
                "SELECT nombre as materia_prima, stock_actual, stock_minimo
                 FROM materias_primas 
                 WHERE stock_actual <= stock_minimo 
                 AND estado = 'activo'
                 ORDER BY (stock_actual - stock_minimo) ASC"
            );
            
            echo json_encode(['success' => true, 'alertas' => $alertas]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
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