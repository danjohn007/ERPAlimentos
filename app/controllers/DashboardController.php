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
            // Obtener estadísticas básicas
            $db = Database::getInstance();
            
            // Estadísticas de producción
            $produccionHoy = $db->queryOne("
                SELECT COUNT(*) as total, SUM(cantidad_producida) as kg_producidos 
                FROM lotes_produccion 
                WHERE DATE(fecha_inicio) = CURDATE() AND estado = 'terminado'
            ") ?? $produccionHoy;
            
            // Estadísticas de inventario
            $inventarioProductos = $db->queryOne("
                SELECT SUM(cantidad) as total_kg 
                FROM inventario 
                WHERE tipo = 'producto_terminado' AND estado = 'disponible'
            ") ?? $inventarioProductos;
            
            // Productos próximos a vencer (próximos 7 días)
            $proximosVencer = $db->query("
                SELECT p.nombre, i.cantidad, i.fecha_caducidad 
                FROM inventario i 
                JOIN productos p ON i.item_id = p.id 
                WHERE i.tipo = 'producto_terminado' 
                AND i.estado = 'disponible' 
                AND i.fecha_caducidad BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                ORDER BY i.fecha_caducidad ASC
                LIMIT 5
            ") ?? [];
            
            // Lotes en proceso
            $lotesEnProceso = $db->query("
                SELECT lp.numero_lote, p.nombre as producto, lp.fecha_inicio, lp.estado
                FROM lotes_produccion lp
                JOIN recetas r ON lp.receta_id = r.id
                JOIN productos p ON r.producto_id = p.id
                WHERE lp.estado IN ('programado', 'en_proceso')
                ORDER BY lp.fecha_inicio ASC
                LIMIT 5
            ") ?? [];
            
            // Ventas del mes
            $ventasMes = $db->queryOne("
                SELECT COUNT(*) as total_ordenes, SUM(total) as total_ventas
                FROM ventas 
                WHERE MONTH(fecha_venta) = MONTH(CURDATE()) 
                AND YEAR(fecha_venta) = YEAR(CURDATE())
            ") ?? $ventasMes;
            
        } catch (Exception $e) {
            // En caso de error de base de datos, usar datos demo
            if (DEBUG_MODE) {
                $this->setFlash('warning', 'Base de datos no conectada, mostrando datos demo');
            }
            
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