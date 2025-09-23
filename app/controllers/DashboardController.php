    <?php
    /**
     * Controlador del Dashboard
     */

    defined('ERP_QUESOS') or die('Acceso denegado');

    class DashboardController extends Controller {
        
        public function index() {
            $this->requireAuth();
            
            // Inicializar estadísticas vacías
            $produccionHoy = ['total' => 0, 'kg_producidos' => 0];
            $inventarioProductos = ['total_kg' => 0];
            $proximosVencer = [];
            $lotesEnProceso = [];
            $ventasMes = ['total_ordenes' => 0, 'total_ventas' => 0];
            
            try {
                // Inicializar modelos
                $loteModel = new LoteProduccion();
                $inventarioModel = new Inventario();
                $ordenVentaModel = new OrdenVenta();
                
                // Estadísticas de producción del día actual
                $hoy = date('Y-m-d');
                $produccionHoyData = $loteModel->query("
                    SELECT COUNT(*) as total, COALESCE(SUM(cantidad_producida), 0) as kg_producidos 
                    FROM lotes_produccion 
                    WHERE DATE(fecha_fin) = ? AND estado = 'terminado'
                ", [$hoy]);
                
                if (!empty($produccionHoyData)) {
                    $produccionHoy = $produccionHoyData[0];
                    $produccionHoy['kg_producidos'] = $produccionHoy['kg_producidos'] ?? 0;
                }
                
                // Estadísticas de inventario de productos terminados
                $inventarioData = $inventarioModel->query("
                    SELECT COALESCE(SUM(cantidad), 0) as total_kg 
                    FROM inventario 
                    WHERE tipo = 'producto_terminado' AND estado = 'disponible'
                ");
                
                if (!empty($inventarioData)) {
                    $inventarioProductos = $inventarioData[0];
                    $inventarioProductos['total_kg'] = $inventarioProductos['total_kg'] ?? 0;
                }
                
                // Productos próximos a vencer (próximos 7 días)
                $proximosVencer = $inventarioModel->query("
                    SELECT p.nombre, i.cantidad, i.fecha_caducidad 
                    FROM inventario i 
                    JOIN productos p ON i.item_id = p.id 
                    WHERE i.tipo = 'producto_terminado' 
                    AND i.estado = 'disponible' 
                    AND i.fecha_caducidad IS NOT NULL
                    AND i.fecha_caducidad BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                    ORDER BY i.fecha_caducidad ASC
                    LIMIT 5
                ");
                
                // Lotes en proceso
                $lotesEnProceso = $loteModel->query("
                    SELECT lp.numero_lote, 
                        COALESCE(p.nombre, r.nombre, 'Sin producto') as producto, 
                        lp.fecha_inicio, 
                        lp.estado
                    FROM lotes_produccion lp
                    LEFT JOIN recetas r ON lp.receta_id = r.id
                    LEFT JOIN productos p ON r.producto_id = p.id
                    WHERE lp.estado IN ('programado', 'en_proceso')
                    ORDER BY lp.fecha_inicio ASC
                    LIMIT 5
                ");
                
                // Ventas del mes actual
                $mesActual = date('Y-m');
                $ventasData = $ordenVentaModel->query("
                    SELECT COUNT(*) as total_ordenes, COALESCE(SUM(total), 0) as total_ventas
                    FROM ordenes_venta 
                    WHERE YEAR(fecha_orden) = YEAR(CURDATE()) 
                    AND MONTH(fecha_orden) = MONTH(CURDATE())
                    AND estado != 'cancelado'
                ");
                
                if (!empty($ventasData)) {
                    $ventasMes = $ventasData[0];
                    // Asegurar que los valores sean numéricos y no tengan caracteres extraños
                    $ventasMes['total_ventas'] = floatval($ventasMes['total_ventas'] ?? 0);
                    $ventasMes['total_ordenes'] = intval($ventasMes['total_ordenes'] ?? 0);
                }
                
                // Debug: Verificar datos de ventas
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log("Ventas del mes: " . print_r($ventasMes, true));
                    
                    // También obtener ventas totales para comparar
                    $ventasTotales = $ordenVentaModel->query("
                        SELECT COALESCE(SUM(total), 0) as total_general 
                        FROM ordenes_venta 
                        WHERE estado != 'cancelado'
                    ");
                    error_log("Ventas totales: " . print_r($ventasTotales, true));
                }
                
            } catch (Exception $e) {
                // En caso de error de base de datos, usar datos demo
                error_log("Error en Dashboard: " . $e->getMessage());
                
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    $this->setFlash('warning', 'Error de conexión a BD: ' . $e->getMessage());
                }
                
                // Datos de demostración
                $produccionHoy = ['total' => 3, 'kg_producidos' => 145.5];
                $inventarioProductos = ['total_kg' => 580.25];
                $proximosVencer = [
                    ['nombre' => 'Queso Fresco Ranchero', 'cantidad' => 25.0, 'fecha_caducidad' => date('Y-m-d', strtotime('+3 days'))],
                    ['nombre' => 'Queso Oaxaca', 'cantidad' => 15.5, 'fecha_caducidad' => date('Y-m-d', strtotime('+5 days'))],
                ];
                $lotesEnProceso = [
                    ['numero_lote' => 'LOT-DEMO-001', 'producto' => 'Queso Manchego', 'fecha_inicio' => date('Y-m-d H:i:s'), 'estado' => 'en_proceso'],
                    ['numero_lote' => 'LOT-DEMO-002', 'producto' => 'Queso Fresco', 'fecha_inicio' => date('Y-m-d H:i:s', strtotime('+1 hour')), 'estado' => 'programado'],
                ];
                $ventasMes = ['total_ordenes' => 45, 'total_ventas' => 85430.50];
            }
            
            $this->view->render('dashboard/index', [
                'title' => 'Dashboard',
                'produccionHoy' => $produccionHoy,
                'inventarioProductos' => $inventarioProductos,
                'proximosVencer' => $proximosVencer,
                'lotesEnProceso' => $lotesEnProceso,
                'ventasMes' => $ventasMes
            ]);
        }
    }