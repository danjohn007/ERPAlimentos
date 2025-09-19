<?php
/**
 * Modelo de Recetas
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Receta extends Model {
    protected $table = 'recetas';
    
    /**
     * Obtener recetas activas con información del producto
     */
    public function getRecetasConProducto() {
        $sql = "SELECT r.*, p.nombre as producto_nombre, p.tipo as producto_tipo 
                FROM {$this->table} r
                INNER JOIN productos p ON r.producto_id = p.id
                WHERE r.estado = 'activo'
                ORDER BY r.nombre";
        return $this->query($sql);
    }
    
    /**
     * Obtener receta con detalles completos
     */
    public function getRecetaCompleta($id) {
        $sql = "SELECT r.*, p.nombre as producto_nombre, p.tipo as producto_tipo,
                       p.codigo as producto_codigo
                FROM {$this->table} r
                INNER JOIN productos p ON r.producto_id = p.id
                WHERE r.id = ? AND r.estado = 'activo'";
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Obtener ingredientes de una receta
     */
    public function getIngredientes($recetaId) {
        $sql = "SELECT ri.*, mp.nombre as materia_prima_nombre, mp.unidad_medida
                FROM receta_ingredientes ri
                INNER JOIN materias_primas mp ON ri.materia_prima_id = mp.id
                WHERE ri.receta_id = ?
                ORDER BY ri.orden";
        return $this->query($sql, [$recetaId]);
    }
    
    /**
     * Calcular costo de producción de una receta
     */
    public function calcularCosto($recetaId) {
        $sql = "SELECT SUM(ri.cantidad * mp.costo_unitario) as costo_total
                FROM receta_ingredientes ri
                INNER JOIN materias_primas mp ON ri.materia_prima_id = mp.id
                WHERE ri.receta_id = ?";
        $result = $this->queryOne($sql, [$recetaId]);
        return $result ? $result['costo_total'] : 0;
    }
    
    /**
     * Buscar recetas por nombre
     */
    public function buscar($termino) {
        $sql = "SELECT r.*, p.nombre as producto_nombre
                FROM {$this->table} r
                INNER JOIN productos p ON r.producto_id = p.id
                WHERE (r.nombre LIKE ? OR r.codigo LIKE ?) AND r.estado = 'activo'
                ORDER BY r.nombre";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino]);
    }
}