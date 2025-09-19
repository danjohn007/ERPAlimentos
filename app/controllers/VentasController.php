<?php
/**
 * Controlador de Ventas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class VentasController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $ordenModel = new OrdenVenta();
        $clienteModel = new Cliente();
        
        $ordenesPendientes = $ordenModel->getByEstado('pendiente');
        $ordenesProceso = $ordenModel->getByEstado('proceso');
        $ordenesDia = $ordenModel->query(
            "SELECT COUNT(*) as total, SUM(total) as monto FROM ordenes_venta WHERE DATE(fecha_orden) = CURDATE()"
        );
        $clientesActivos = $clienteModel->getActivos();
        
        $ventasDia = $ordenesDia[0] ?? ['total' => 0, 'monto' => 0];
        
        $this->view->render('modules/ventas/index', [
            'title' => 'Módulo de Ventas',
            'ordenes_pendientes' => $ordenesPendientes,
            'ordenes_proceso' => $ordenesProceso,
            'ventas_dia' => $ventasDia,
            'clientes_activos' => $clientesActivos
        ]);
    }
    
    public function clientes() {
        $this->requireAuth();
        
        $clienteModel = new Cliente();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarCliente();
            return;
        }
        
        $clientes = $clienteModel->getActivos();
        
        $this->view->render('modules/ventas/clientes', [
            'title' => 'Gestión de Clientes',
            'clientes' => $clientes
        ]);
    }
    
    public function nuevo_cliente() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarCliente();
            return;
        }
        
        $this->view->render('modules/ventas/nuevo_cliente', [
            'title' => 'Nuevo Cliente'
        ]);
    }
    
    public function ver_cliente($id) {
        $this->requireAuth();
        
        $clienteModel = new Cliente();
        $cliente = $clienteModel->getById($id);
        
        if (!$cliente) {
            header('Location: /ventas/clientes');
            exit;
        }
        
        $estadisticas = $clienteModel->getEstadisticas($id);
        $ordenModel = new OrdenVenta();
        $ordenesCliente = $ordenModel->query(
            "SELECT * FROM ordenes_venta WHERE cliente_id = ? ORDER BY fecha_orden DESC LIMIT 10",
            [$id]
        );
        
        $this->view->render('modules/ventas/ver_cliente', [
            'title' => 'Cliente: ' . $cliente['nombre'],
            'cliente' => $cliente,
            'estadisticas' => $estadisticas,
            'ordenes_recientes' => $ordenesCliente
        ]);
    }
    
    public function editar_cliente($id) {
        $this->requireAuth();
        
        $clienteModel = new Cliente();
        $cliente = $clienteModel->getById($id);
        
        if (!$cliente) {
            header('Location: /ventas/clientes');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarCliente($id);
            return;
        }
        
        $this->view->render('modules/ventas/editar_cliente', [
            'title' => 'Editar Cliente',
            'cliente' => $cliente
        ]);
    }
    
    public function ordenes() {
        $this->requireAuth();
        
        $ordenModel = new OrdenVenta();
        $ordenes = $ordenModel->getOrdenesConCliente();
        
        $this->view->render('modules/ventas/ordenes', [
            'title' => 'Órdenes de Venta',
            'ordenes' => $ordenes
        ]);
    }
    
    public function nueva_orden() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarNuevaOrden();
            return;
        }
        
        $clienteModel = new Cliente();
        $productoModel = new Producto();
        
        $clientes = $clienteModel->getActivos();
        $productos = $productoModel->getActivos();
        
        $this->view->render('modules/ventas/nueva_orden', [
            'title' => 'Nueva Orden de Venta',
            'clientes' => $clientes,
            'productos' => $productos
        ]);
    }
    
    public function ver_orden($id) {
        $this->requireAuth();
        
        $ordenModel = new OrdenVenta();
        $orden = $ordenModel->getOrdenCompleta($id);
        $detalles = $ordenModel->getDetallesOrden($id);
        
        if (!$orden) {
            header('Location: /ventas/ordenes');
            exit;
        }
        
        $this->view->render('modules/ventas/ver_orden', [
            'title' => 'Orden de Venta #' . $orden['numero_orden'],
            'orden' => $orden,
            'detalles' => $detalles
        ]);
    }
    
    public function facturacion() {
        $this->requireAuth();
        
        $ordenModel = new OrdenVenta();
        $ordenesPorFacturar = $ordenModel->getByEstado('entregado');
        
        $this->view->render('modules/ventas/facturacion', [
            'title' => 'Facturación',
            'ordenes_por_facturar' => $ordenesPorFacturar
        ]);
    }
    
    public function cambiar_estado_orden() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $ordenModel = new OrdenVenta();
            $ordenId = $input['orden_id'];
            $nuevoEstado = $input['estado'];
            
            $orden = $ordenModel->getById($ordenId);
            if (!$orden) {
                echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
                return;
            }
            
            $ordenModel->actualizarEstado($ordenId, $nuevoEstado);
            
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function cancelar_orden() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $ordenModel = new OrdenVenta();
            $ordenId = $input['orden_id'];
            $motivo = $input['motivo'];
            
            $orden = $ordenModel->getById($ordenId);
            if (!$orden) {
                echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
                return;
            }
            
            $observaciones = $orden['observaciones'] . "\nCancelada: " . $motivo . " (Usuario: " . $_SESSION['user_name'] . ", Fecha: " . date('Y-m-d H:i:s') . ")";
            
            $ordenModel->update($ordenId, [
                'estado' => 'cancelado',
                'observaciones' => $observaciones
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Orden cancelada correctamente']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    private function procesarCliente($id = null) {
        try {
            $clienteModel = new Cliente();
            
            $datos = [
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'tipo' => $_POST['tipo'],
                'contacto' => $_POST['contacto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'rfc' => $_POST['rfc'] ?? '',
                'credito_limite' => floatval($_POST['credito_limite'] ?? 0),
                'descuento_porcentaje' => floatval($_POST['descuento_porcentaje'] ?? 0),
                'estado' => 'activo'
            ];
            
            if ($id) {
                // Actualizar cliente existente
                $clienteModel->update($id, $datos);
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
                header('Location: /ventas/ver_cliente/' . $id);
            } else {
                // Crear nuevo cliente
                $clienteId = $clienteModel->insert($datos);
                $_SESSION['success'] = 'Cliente creado exitosamente';
                header('Location: /ventas/ver_cliente/' . $clienteId);
            }
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al procesar el cliente: ' . $e->getMessage();
            if ($id) {
                header('Location: /ventas/editar_cliente/' . $id);
            } else {
                header('Location: /ventas/nuevo_cliente');
            }
            exit;
        }
    }
    
    private function procesarNuevaOrden() {
        try {
            $ordenModel = new OrdenVenta();
            
            $numeroOrden = $ordenModel->generarNumeroOrden();
            
            $datosOrden = [
                'numero_orden' => $numeroOrden,
                'cliente_id' => $_POST['cliente_id'],
                'fecha_orden' => date('Y-m-d H:i:s'),
                'fecha_entrega' => $_POST['fecha_entrega'] ?? null,
                'observaciones' => $_POST['observaciones'] ?? '',
                'vendedor_id' => $_SESSION['user_id'],
                'estado' => 'pendiente'
            ];
            
            $ordenId = $ordenModel->insert($datosOrden);
            
            if ($ordenId && isset($_POST['detalles'])) {
                $this->procesarDetallesOrden($ordenId, $_POST['detalles']);
                $totales = $this->calcularTotalOrden($ordenId);
                
                $ordenModel->update($ordenId, [
                    'subtotal' => $totales['subtotal'],
                    'descuento' => $totales['descuento'],
                    'impuestos' => $totales['impuestos'],
                    'total' => $totales['total'],
                    'saldo_pendiente' => $totales['total']
                ]);
            }
            
            $_SESSION['success'] = 'Orden de venta creada exitosamente';
            header('Location: /ventas/ver_orden/' . $ordenId);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear la orden: ' . $e->getMessage();
            header('Location: /ventas/nueva_orden');
            exit;
        }
    }
    
    private function procesarDetallesOrden($ordenId, $detalles) {
        foreach ($detalles as $detalle) {
            if (empty($detalle['producto_id']) || empty($detalle['cantidad'])) {
                continue;
            }
            
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
            $descuento = $detalle['descuento'] ?? 0;
            $subtotalFinal = $subtotal - $descuento;
            
            $datosDetalle = [
                'orden_venta_id' => $ordenId,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descuento' => $descuento,
                'subtotal' => $subtotalFinal,
                'observaciones' => $detalle['observaciones'] ?? ''
            ];
            
            $this->db->query(
                "INSERT INTO orden_venta_detalles (orden_venta_id, producto_id, cantidad, precio_unitario, descuento, subtotal, observaciones) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                array_values($datosDetalle)
            );
        }
    }
    
    private function calcularTotalOrden($ordenId) {
        $ordenModel = new OrdenVenta();
        $detalles = $ordenModel->getDetallesOrden($ordenId);
        
        $subtotal = 0;
        $descuentoTotal = 0;
        
        foreach ($detalles as $detalle) {
            $subtotal += $detalle['subtotal'];
            $descuentoTotal += $detalle['descuento'];
        }
        
        $impuestos = $subtotal * 0.16; // IVA 16%
        $total = $subtotal + $impuestos;
        
        return [
            'subtotal' => $subtotal,
            'descuento' => $descuentoTotal,
            'impuestos' => $impuestos,
            'total' => $total
        ];
    }
}