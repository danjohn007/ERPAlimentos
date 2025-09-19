<?php
/**
 * Modelo de Productos
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Producto extends Model {
    protected $table = 'productos';
    
    /**
     * Obtener productos activos
     */
    public function getActivos() {
        return $this->findBy('estado', 'activo');
    }
    
    /**
     * Obtener productos por tipo
     */
    public function getByTipo($tipo) {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = ? AND estado = 'activo' ORDER BY nombre";
        return $this->query($sql, [$tipo]);
    }
    
    /**
     * Obtener productos con stock bajo
     */
    public function getStockBajo() {
        $sql = "SELECT * FROM {$this->table} WHERE stock_actual <= stock_minimo AND estado = 'activo' ORDER BY stock_actual";
        return $this->query($sql);
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
     * Buscar productos por nombre o código
     */
    public function buscar($termino) {
        $sql = "SELECT * FROM {$this->table} WHERE (nombre LIKE ? OR codigo LIKE ?) AND estado = 'activo' ORDER BY nombre";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino]);
    }
}