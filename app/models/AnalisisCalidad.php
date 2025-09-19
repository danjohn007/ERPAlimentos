<?php
/**
 * Modelo de Análisis de Calidad
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class AnalisisCalidad extends Model {
    protected $table = 'analisis_calidad';
    
    /**
     * Obtener análisis recientes con información completa
     */
    public function getAnalisisRecientes($limite = 20) {
        $sql = "SELECT ac.*, 
                       CASE 
                           WHEN ac.tipo = 'materia_prima' THEN mp.nombre
                           WHEN ac.tipo = 'producto_terminado' THEN p.nombre
                           WHEN ac.tipo = 'proceso' THEN CONCAT('Lote ', lp.numero_lote)
                       END as item_nombre,
                       lp.numero_lote,
                       u.nombre as analista_nombre,
                       u.apellidos as analista_apellidos
                FROM {$this->table} ac
                LEFT JOIN materias_primas mp ON ac.tipo = 'materia_prima' AND ac.item_id = mp.id
                LEFT JOIN productos p ON ac.tipo = 'producto_terminado' AND ac.item_id = p.id
                LEFT JOIN lotes_produccion lp ON ac.lote_produccion_id = lp.id
                LEFT JOIN usuarios u ON ac.analista_id = u.id
                ORDER BY ac.fecha_analisis DESC
                LIMIT ?";
        return $this->query($sql, [$limite]);
    }
    
    /**
     * Obtener análisis por tipo
     */
    public function getAnalisisPorTipo($tipo) {
        $sql = "SELECT ac.*, 
                       CASE 
                           WHEN ac.tipo = 'materia_prima' THEN mp.nombre
                           WHEN ac.tipo = 'producto_terminado' THEN p.nombre
                           WHEN ac.tipo = 'proceso' THEN CONCAT('Lote ', lp.numero_lote)
                       END as item_nombre,
                       u.nombre as analista_nombre
                FROM {$this->table} ac
                LEFT JOIN materias_primas mp ON ac.tipo = 'materia_prima' AND ac.item_id = mp.id
                LEFT JOIN productos p ON ac.tipo = 'producto_terminado' AND ac.item_id = p.id
                LEFT JOIN lotes_produccion lp ON ac.lote_produccion_id = lp.id
                LEFT JOIN usuarios u ON ac.analista_id = u.id
                WHERE ac.tipo = ?
                ORDER BY ac.fecha_analisis DESC";
        return $this->query($sql, [$tipo]);
    }
    
    /**
     * Obtener análisis por resultado
     */
    public function getAnalisisPorResultado($resultado) {
        return $this->findBy('resultado', $resultado);
    }
    
    /**
     * Obtener análisis no conformes
     */
    public function getAnalisisNoConformes() {
        $sql = "SELECT ac.*, 
                       CASE 
                           WHEN ac.tipo = 'materia_prima' THEN mp.nombre
                           WHEN ac.tipo = 'producto_terminado' THEN p.nombre
                           WHEN ac.tipo = 'proceso' THEN CONCAT('Lote ', lp.numero_lote)
                       END as item_nombre,
                       u.nombre as analista_nombre
                FROM {$this->table} ac
                LEFT JOIN materias_primas mp ON ac.tipo = 'materia_prima' AND ac.item_id = mp.id
                LEFT JOIN productos p ON ac.tipo = 'producto_terminado' AND ac.item_id = p.id
                LEFT JOIN lotes_produccion lp ON ac.lote_produccion_id = lp.id
                LEFT JOIN usuarios u ON ac.analista_id = u.id
                WHERE ac.resultado IN ('no_conforme', 'requiere_revision')
                ORDER BY ac.fecha_analisis DESC";
        return $this->query($sql);
    }
    
    /**
     * Obtener análisis de un lote específico
     */
    public function getAnalisisLote($loteId) {
        $sql = "SELECT ac.*, u.nombre as analista_nombre, u.apellidos as analista_apellidos
                FROM {$this->table} ac
                LEFT JOIN usuarios u ON ac.analista_id = u.id
                WHERE ac.lote_produccion_id = ?
                ORDER BY ac.fecha_analisis DESC";
        return $this->query($sql, [$loteId]);
    }
    
    /**
     * Obtener estadísticas de calidad
     */
    public function getEstadisticasCalidad($fechaInicio = null, $fechaFin = null) {
        $whereClause = "1=1";
        $params = [];
        
        if ($fechaInicio && $fechaFin) {
            $whereClause .= " AND DATE(fecha_analisis) BETWEEN ? AND ?";
            $params = [$fechaInicio, $fechaFin];
        }
        
        $sql = "SELECT 
                    resultado,
                    COUNT(*) as total,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM {$this->table} WHERE $whereClause)), 2) as porcentaje
                FROM {$this->table} 
                WHERE $whereClause
                GROUP BY resultado";
        
        return $this->query($sql, array_merge($params, $params));
    }
    
    /**
     * Registrar nuevo análisis
     */
    public function registrarAnalisis($datos) {
        $campos = [
            'tipo', 'item_id', 'lote_produccion_id', 'fecha_analisis', 'analista_id',
            'ph', 'humedad', 'temperatura', 'grasa', 'proteina', 'sal',
            'microbiologia', 'observaciones', 'resultado'
        ];
        
        $data = [];
        foreach ($campos as $campo) {
            if (isset($datos[$campo])) {
                $data[$campo] = $datos[$campo];
            }
        }
        
        return $this->insert($data);
    }
    
    /**
     * Verificar si requiere análisis
     */
    public function requiereAnalisis($tipo, $itemId, $loteId = null) {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table}
                WHERE tipo = ? AND item_id = ?";
        $params = [$tipo, $itemId];
        
        if ($loteId) {
            $sql .= " AND lote_produccion_id = ?";
            $params[] = $loteId;
        }
        
        $sql .= " AND DATE(fecha_analisis) = CURDATE()";
        
        $result = $this->queryOne($sql, $params);
        return $result['total'] == 0;
    }
}