<?php
/**
 * Modelo de Lotes de Producción
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class LoteProduccion extends Model {
    protected $table = 'lotes_produccion';
    
    /**
     * Obtener lotes con información de receta y operadores
     */
    public function getLotesConDetalles() {
        $sql = "SELECT lp.*, r.nombre as receta_nombre, r.codigo as receta_codigo,
                       p.nombre as producto_nombre,
                       uo.nombre as operador_nombre, uo.apellidos as operador_apellidos,
                       us.nombre as supervisor_nombre, us.apellidos as supervisor_apellidos
                FROM {$this->table} lp
                INNER JOIN recetas r ON lp.receta_id = r.id
                INNER JOIN productos p ON r.producto_id = p.id
                LEFT JOIN usuarios uo ON lp.operador_id = uo.id
                LEFT JOIN usuarios us ON lp.supervisor_id = us.id
                ORDER BY lp.fecha_inicio DESC";
        return $this->query($sql);
    }
    
    /**
     * Obtener lote con detalles completos
     */
    public function getLoteCompleto($id) {
        $sql = "SELECT lp.*, r.nombre as receta_nombre, r.codigo as receta_codigo,
                       r.tiempo_maduracion, r.temperatura_proceso as temp_receta,
                       r.ph_optimo, r.humedad_maduracion as humedad_receta,
                       p.nombre as producto_nombre, p.codigo as producto_codigo,
                       uo.nombre as operador_nombre, uo.apellidos as operador_apellidos,
                       us.nombre as supervisor_nombre, us.apellidos as supervisor_apellidos
                FROM {$this->table} lp
                INNER JOIN recetas r ON lp.receta_id = r.id
                INNER JOIN productos p ON r.producto_id = p.id
                LEFT JOIN usuarios uo ON lp.operador_id = uo.id
                LEFT JOIN usuarios us ON lp.supervisor_id = us.id
                WHERE lp.id = ?";
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Obtener lotes en proceso
     */
    public function getLotesEnProceso() {
        return $this->findBy('estado', 'en_proceso');
    }
    
    /**
     * Obtener lotes programados
     */
    public function getLotesProgramados() {
        return $this->findBy('estado', 'programado');
    }
    
    /**
     * Obtener lotes que requieren seguimiento de maduración
     */
    public function getLotesEnMaduracion() {
        $sql = "SELECT lp.*, r.nombre as receta_nombre, r.tiempo_maduracion,
                       p.nombre as producto_nombre,
                       DATEDIFF(NOW(), lp.fecha_fin) as dias_maduracion,
                       (r.tiempo_maduracion - DATEDIFF(NOW(), lp.fecha_fin)) as dias_restantes
                FROM {$this->table} lp
                INNER JOIN recetas r ON lp.receta_id = r.id
                INNER JOIN productos p ON r.producto_id = p.id
                WHERE lp.estado = 'terminado' 
                AND r.tiempo_maduracion > 0
                AND DATEDIFF(NOW(), lp.fecha_fin) < r.tiempo_maduracion
                ORDER BY dias_restantes ASC";
        return $this->query($sql);
    }
    
    /**
     * Generar siguiente número de lote
     */
    public function generarNumeroLote() {
        $fecha = date('Ymd');
        $sql = "SELECT COUNT(*) + 1 as siguiente FROM {$this->table} WHERE numero_lote LIKE ?";
        $result = $this->queryOne($sql, ["LOT-$fecha-%"]);
        $secuencia = str_pad($result['siguiente'], 3, '0', STR_PAD_LEFT);
        return "LOT-$fecha-$secuencia";
    }
    
    /**
     * Actualizar estado del lote
     */
    public function actualizarEstado($id, $estado, $observaciones = null) {
        $data = ['estado' => $estado];
        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }
        if ($estado === 'terminado') {
            $data['fecha_fin'] = date('Y-m-d H:i:s');
        }
        return $this->update($id, $data);
    }
}