<?php
/**
 * Modelo de Materias Primas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class MateriaPrima extends Model {
    protected $table = 'materias_primas';
    
    /**
     * Obtener materias primas activas con proveedor
     */
    public function getConProveedor() {
        $sql = "SELECT mp.*, p.nombre as proveedor_nombre, p.certificaciones
                FROM {$this->table} mp
                LEFT JOIN proveedores p ON mp.proveedor_principal_id = p.id
                WHERE mp.estado = 'activo'
                ORDER BY mp.tipo, mp.nombre";
        return $this->query($sql);
    }
    
    /**
     * Obtener por tipo
     */
    public function getByTipo($tipo) {
        return $this->findBy('tipo', $tipo);
    }
    
    /**
     * Obtener materias primas con stock bajo
     */
    public function getStockBajo() {
        $sql = "SELECT mp.*, p.nombre as proveedor_nombre
                FROM {$this->table} mp
                LEFT JOIN proveedores p ON mp.proveedor_principal_id = p.id
                WHERE mp.stock_actual <= mp.stock_minimo AND mp.estado = 'activo'
                ORDER BY mp.stock_actual";
        return $this->query($sql);
    }
    
    /**
     * Obtener materias primas próximas a vencer
     */
    public function getProximasVencer($dias = 7) {
        $sql = "SELECT mp.*, i.fecha_caducidad, i.cantidad as stock_lote,
                       DATEDIFF(i.fecha_caducidad, NOW()) as dias_restantes
                FROM {$this->table} mp
                INNER JOIN inventario i ON mp.id = i.item_id AND i.tipo = 'materia_prima'
                WHERE i.fecha_caducidad <= DATE_ADD(NOW(), INTERVAL ? DAY)
                AND i.fecha_caducidad > NOW()
                AND i.estado = 'disponible'
                AND mp.estado = 'activo'
                ORDER BY i.fecha_caducidad";
        return $this->query($sql, [$dias]);
    }
    
    /**
     * Actualizar stock
     */
    public function actualizarStock($id, $cantidad, $operacion = 'suma') {
        $operador = ($operacion === 'suma') ? '+' : '-';
        $sql = "UPDATE {$this->table} SET stock_actual = stock_actual $operador ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cantidad, $id]);
    }
    
    /**
     * Buscar materias primas
     */
    public function buscar($termino) {
        $sql = "SELECT mp.*, p.nombre as proveedor_nombre
                FROM {$this->table} mp
                LEFT JOIN proveedores p ON mp.proveedor_principal_id = p.id
                WHERE (mp.nombre LIKE ? OR mp.codigo LIKE ?) AND mp.estado = 'activo'
                ORDER BY mp.nombre";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino]);
    }
    
    /**
     * Obtener inventario actual por ubicación
     */
    public function getInventarioPorUbicacion() {
        $sql = "SELECT i.ubicacion, mp.nombre, mp.codigo, mp.tipo,
                       SUM(i.cantidad) as cantidad_total,
                       MIN(i.fecha_caducidad) as proxima_caducidad
                FROM inventario i
                INNER JOIN {$this->table} mp ON i.item_id = mp.id
                WHERE i.tipo = 'materia_prima' AND i.estado = 'disponible'
                GROUP BY i.ubicacion, mp.id
                ORDER BY i.ubicacion, mp.nombre";
        return $this->query($sql);
    }
}