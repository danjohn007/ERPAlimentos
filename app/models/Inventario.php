<?php
/**
 * Modelo de Inventario
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Inventario extends Model {
    protected $table = 'inventario';
    
    /**
     * Obtener inventario con detalles del item
     */
    public function getInventarioCompleto() {
        $sql = "SELECT i.*, 
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.nombre
                           WHEN i.tipo = 'producto_terminado' THEN p.nombre
                       END as item_nombre,
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.codigo
                           WHEN i.tipo = 'producto_terminado' THEN p.codigo
                       END as item_codigo,
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.unidad_medida
                           WHEN i.tipo = 'producto_terminado' THEN p.unidad_medida
                       END as unidad_medida,
                       pr.nombre as proveedor_nombre,
                       lp.numero_lote as lote_produccion
                FROM {$this->table} i
                LEFT JOIN materias_primas mp ON i.tipo = 'materia_prima' AND i.item_id = mp.id
                LEFT JOIN productos p ON i.tipo = 'producto_terminado' AND i.item_id = p.id
                LEFT JOIN proveedores pr ON i.proveedor_id = pr.id
                LEFT JOIN lotes_produccion lp ON i.lote_produccion_id = lp.id
                WHERE i.estado = 'disponible'
                ORDER BY i.ubicacion, i.fecha_caducidad";
        return $this->query($sql);
    }
    
    /**
     * Obtener inventario por ubicación
     */
    public function getByUbicacion($ubicacion) {
        $sql = "SELECT i.*, 
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.nombre
                           WHEN i.tipo = 'producto_terminado' THEN p.nombre
                       END as item_nombre
                FROM {$this->table} i
                LEFT JOIN materias_primas mp ON i.tipo = 'materia_prima' AND i.item_id = mp.id
                LEFT JOIN productos p ON i.tipo = 'producto_terminado' AND i.item_id = p.id
                WHERE i.ubicacion = ? AND i.estado = 'disponible'
                ORDER BY i.fecha_caducidad";
        return $this->query($sql, [$ubicacion]);
    }
    
    /**
     * Obtener productos próximos a vencer
     */
    public function getProximosVencer($dias = 7) {
        $sql = "SELECT i.*, 
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.nombre
                           WHEN i.tipo = 'producto_terminado' THEN p.nombre
                       END as item_nombre,
                       DATEDIFF(i.fecha_caducidad, NOW()) as dias_restantes
                FROM {$this->table} i
                LEFT JOIN materias_primas mp ON i.tipo = 'materia_prima' AND i.item_id = mp.id
                LEFT JOIN productos p ON i.tipo = 'producto_terminado' AND i.item_id = p.id
                WHERE i.fecha_caducidad <= DATE_ADD(NOW(), INTERVAL ? DAY)
                AND i.fecha_caducidad > NOW()
                AND i.estado = 'disponible'
                ORDER BY i.fecha_caducidad";
        return $this->query($sql, [$dias]);
    }
    
    /**
     * Obtener productos vencidos
     */
    public function getVencidos() {
        $sql = "SELECT i.*, 
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.nombre
                           WHEN i.tipo = 'producto_terminado' THEN p.nombre
                       END as item_nombre
                FROM {$this->table} i
                LEFT JOIN materias_primas mp ON i.tipo = 'materia_prima' AND i.item_id = mp.id
                LEFT JOIN productos p ON i.tipo = 'producto_terminado' AND i.item_id = p.id
                WHERE i.fecha_caducidad < NOW()
                AND i.estado = 'disponible'
                ORDER BY i.fecha_caducidad";
        return $this->query($sql);
    }
    
    /**
     * Registrar entrada de inventario
     */
    public function registrarEntrada($datos) {
        return $this->create($datos);
    }
    
    /**
     * Registrar salida de inventario
     */
    public function registrarSalida($id, $cantidad) {
        $item = $this->getById($id);
        if (!$item) return false;
        
        if ($item['cantidad'] >= $cantidad) {
            $nuevaCantidad = $item['cantidad'] - $cantidad;
            if ($nuevaCantidad == 0) {
                return $this->update($id, ['cantidad' => 0, 'estado' => 'agotado']);
            } else {
                return $this->update($id, ['cantidad' => $nuevaCantidad]);
            }
        }
        return false;
    }
    
    /**
     * Obtener resumen de inventario por tipo
     */
    public function getResumenPorTipo() {
        $sql = "SELECT i.tipo,
                       COUNT(*) as total_items,
                       SUM(i.cantidad) as cantidad_total,
                       SUM(i.costo_total) as valor_total
                FROM {$this->table} i
                WHERE i.estado = 'disponible'
                GROUP BY i.tipo";
        return $this->query($sql);
    }
    
    /**
     * Buscar en inventario
     */
    public function buscar($termino) {
        $sql = "SELECT i.*, 
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.nombre
                           WHEN i.tipo = 'producto_terminado' THEN p.nombre
                       END as item_nombre,
                       CASE 
                           WHEN i.tipo = 'materia_prima' THEN mp.codigo
                           WHEN i.tipo = 'producto_terminado' THEN p.codigo
                       END as item_codigo
                FROM {$this->table} i
                LEFT JOIN materias_primas mp ON i.tipo = 'materia_prima' AND i.item_id = mp.id
                LEFT JOIN productos p ON i.tipo = 'producto_terminado' AND i.item_id = p.id
                WHERE i.estado = 'disponible'
                AND (mp.nombre LIKE ? OR mp.codigo LIKE ? OR p.nombre LIKE ? OR p.codigo LIKE ? OR i.lote LIKE ?)
                ORDER BY i.fecha_caducidad";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino, $termino, $termino, $termino]);
    }
}