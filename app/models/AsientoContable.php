<?php
/**
 * Modelo de Asientos Contables
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class AsientoContable extends Model {
    protected $table = 'asientos_contables';
    
    /**
     * Obtener asientos con información del usuario
     */
    public function getAsientosConUsuario() {
        $sql = "SELECT ac.*, u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                FROM {$this->table} ac
                INNER JOIN usuarios u ON ac.usuario_id = u.id
                ORDER BY ac.fecha DESC, ac.numero_asiento DESC";
        return $this->query($sql);
    }
    
    /**
     * Obtener asientos por estado
     */
    public function getAsientosPorEstado($estado) {
        return $this->findBy('estado', $estado);
    }
    
    /**
     * Obtener asientos por periodo
     */
    public function getAsientosPorPeriodo($fechaInicio, $fechaFin) {
        $sql = "SELECT ac.*, u.nombre as usuario_nombre
                FROM {$this->table} ac
                INNER JOIN usuarios u ON ac.usuario_id = u.id
                WHERE ac.fecha BETWEEN ? AND ?
                ORDER BY ac.fecha DESC, ac.numero_asiento DESC";
        return $this->query($sql, [$fechaInicio, $fechaFin]);
    }
    
    /**
     * Obtener detalles del asiento
     */
    public function getDetallesAsiento($asientoId) {
        $sql = "SELECT acd.*, cc.codigo as cuenta_codigo, cc.nombre as cuenta_nombre
                FROM asiento_contable_detalles acd
                INNER JOIN cuentas_contables cc ON acd.cuenta_contable_id = cc.id
                WHERE acd.asiento_contable_id = ?
                ORDER BY acd.id";
        return $this->query($sql, [$asientoId]);
    }
    
    /**
     * Generar número de asiento
     */
    public function generarNumeroAsiento() {
        $fecha = date('Ymd');
        $sql = "SELECT COUNT(*) + 1 as siguiente FROM {$this->table} WHERE numero_asiento LIKE ?";
        $result = $this->queryOne($sql, ["AS-$fecha-%"]);
        $secuencia = str_pad($result['siguiente'], 4, '0', STR_PAD_LEFT);
        return "AS-$fecha-$secuencia";
    }
    
    /**
     * Crear asiento contable completo
     */
    public function crearAsientoCompleto($datosAsiento, $detalles) {
        try {
            $this->beginTransaction();
            
            // Crear el asiento principal
            $asientoId = $this->insert($datosAsiento);
            
            if (!$asientoId) {
                throw new Exception('Error al crear el asiento contable');
            }
            
            $totalDebe = 0;
            $totalHaber = 0;
            
            // Insertar los detalles
            foreach ($detalles as $detalle) {
                $detalle['asiento_contable_id'] = $asientoId;
                
                $detalleId = $this->query(
                    "INSERT INTO asiento_contable_detalles (asiento_contable_id, cuenta_contable_id, debe, haber, concepto) 
                     VALUES (?, ?, ?, ?, ?)",
                    [$asientoId, $detalle['cuenta_contable_id'], $detalle['debe'], $detalle['haber'], $detalle['concepto'] ?? '']
                );
                
                if (!$detalleId) {
                    throw new Exception('Error al crear detalle del asiento');
                }
                
                $totalDebe += $detalle['debe'];
                $totalHaber += $detalle['haber'];
            }
            
            // Verificar que esté balanceado
            if (abs($totalDebe - $totalHaber) > 0.01) {
                throw new Exception('El asiento no está balanceado');
            }
            
            // Actualizar totales en el asiento
            $this->update($asientoId, [
                'total_debe' => $totalDebe,
                'total_haber' => $totalHaber
            ]);
            
            $this->commit();
            return $asientoId;
            
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    
    /**
     * Confirmar asiento (cambiar estado a confirmado)
     */
    public function confirmarAsiento($asientoId) {
        try {
            $this->beginTransaction();
            
            // Cambiar estado del asiento
            $this->update($asientoId, ['estado' => 'confirmado']);
            
            // Actualizar saldos de las cuentas
            $detalles = $this->getDetallesAsiento($asientoId);
            $cuentaModel = new CuentaContable();
            
            foreach ($detalles as $detalle) {
                $cuentaModel->actualizarSaldo(
                    $detalle['cuenta_contable_id'],
                    $detalle['debe'],
                    $detalle['haber']
                );
            }
            
            $this->commit();
            return true;
            
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    
    /**
     * Anular asiento
     */
    public function anularAsiento($asientoId, $motivo = '') {
        $asiento = $this->getById($asientoId);
        
        if ($asiento['estado'] === 'confirmado') {
            throw new Exception('No se puede anular un asiento confirmado');
        }
        
        return $this->update($asientoId, [
            'estado' => 'anulado',
            'concepto' => $asiento['concepto'] . ' [ANULADO: ' . $motivo . ']'
        ]);
    }
    
    /**
     * Obtener libro diario
     */
    public function getLibroDiario($fechaInicio, $fechaFin) {
        $sql = "SELECT ac.fecha, ac.numero_asiento, ac.concepto, ac.estado,
                       acd.debe, acd.haber, acd.concepto as detalle_concepto,
                       cc.codigo as cuenta_codigo, cc.nombre as cuenta_nombre
                FROM {$this->table} ac
                INNER JOIN asiento_contable_detalles acd ON ac.id = acd.asiento_contable_id
                INNER JOIN cuentas_contables cc ON acd.cuenta_contable_id = cc.id
                WHERE ac.fecha BETWEEN ? AND ? AND ac.estado = 'confirmado'
                ORDER BY ac.fecha, ac.numero_asiento, acd.id";
        
        return $this->query($sql, [$fechaInicio, $fechaFin]);
    }
    
    /**
     * Obtener resumen por tipo de asiento
     */
    public function getResumenPorTipo($fechaInicio = null, $fechaFin = null) {
        $whereClause = "estado = 'confirmado'";
        $params = [];
        
        if ($fechaInicio && $fechaFin) {
            $whereClause .= " AND fecha BETWEEN ? AND ?";
            $params = [$fechaInicio, $fechaFin];
        }
        
        $sql = "SELECT tipo, COUNT(*) as total_asientos, SUM(total_debe) as total_debe, SUM(total_haber) as total_haber
                FROM {$this->table}
                WHERE $whereClause
                GROUP BY tipo
                ORDER BY tipo";
        
        return $this->query($sql, $params);
    }
}