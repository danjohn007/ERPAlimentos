<?php
/**
 * Modelo de Cuentas Contables
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class CuentaContable extends Model {
    protected $table = 'cuentas_contables';
    
    /**
     * Obtener todas las cuentas ordenadas por código
     */
    public function getCuentasOrdenadas() {
        $sql = "SELECT * FROM {$this->table} ORDER BY codigo";
        return $this->query($sql);
    }
    
    /**
     * Obtener cuentas por tipo
     */
    public function getCuentasPorTipo($tipo) {
        return $this->findBy('tipo', $tipo);
    }
    
    /**
     * Obtener cuentas de movimiento (que pueden tener asientos)
     */
    public function getCuentasMovimiento() {
        return $this->findBy('es_movimiento', 1);
    }
    
    /**
     * Obtener cuentas activas
     */
    public function getCuentasActivas() {
        return $this->findBy('estado', 'activa');
    }
    
    /**
     * Obtener estructura jerárquica de cuentas
     */
    public function getEstructuraJerarquica() {
        $sql = "SELECT c1.*, 
                       CASE WHEN c1.cuenta_padre_id IS NULL THEN c1.codigo 
                            ELSE CONCAT(c2.codigo, ' - ', c1.codigo) 
                       END as codigo_completo,
                       c2.nombre as cuenta_padre_nombre
                FROM {$this->table} c1
                LEFT JOIN {$this->table} c2 ON c1.cuenta_padre_id = c2.id
                WHERE c1.estado = 'activa'
                ORDER BY c1.codigo";
        return $this->query($sql);
    }
    
    /**
     * Actualizar saldo de cuenta
     */
    public function actualizarSaldo($cuentaId, $debe = 0, $haber = 0) {
        $cuenta = $this->getById($cuentaId);
        if (!$cuenta) return false;
        
        $nuevoSaldo = $cuenta['saldo_actual'];
        
        // Determinar si aumenta o disminuye según el tipo de cuenta
        switch($cuenta['tipo']) {
            case 'activo':
            case 'gasto':
                $nuevoSaldo += ($debe - $haber);
                break;
            case 'pasivo':
            case 'capital':
            case 'ingreso':
                $nuevoSaldo += ($haber - $debe);
                break;
        }
        
        return $this->update($cuentaId, ['saldo_actual' => $nuevoSaldo]);
    }
    
    /**
     * Obtener balance general
     */
    public function getBalanceGeneral() {
        $sql = "SELECT tipo, SUM(saldo_actual) as total_saldo
                FROM {$this->table}
                WHERE estado = 'activa' AND es_movimiento = 1
                GROUP BY tipo";
        return $this->query($sql);
    }
    
    /**
     * Obtener cuentas por subtipo
     */
    public function getCuentasPorSubtipo($subtipo) {
        return $this->findBy('subtipo', $subtipo);
    }
    
    /**
     * Buscar cuentas
     */
    public function buscarCuentas($termino) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (codigo LIKE ? OR nombre LIKE ?) 
                AND estado = 'activa'
                ORDER BY codigo";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino]);
    }
    
    /**
     * Verificar si se puede eliminar una cuenta
     */
    public function puedeEliminar($cuentaId) {
        // Verificar si tiene asientos contables
        $sql = "SELECT COUNT(*) as total FROM asiento_contable_detalles WHERE cuenta_contable_id = ?";
        $result = $this->queryOne($sql, [$cuentaId]);
        
        if ($result['total'] > 0) {
            return false;
        }
        
        // Verificar si tiene cuentas hijas
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE cuenta_padre_id = ?";
        $result = $this->queryOne($sql, [$cuentaId]);
        
        return $result['total'] == 0;
    }
    
    /**
     * Obtener siguiente código disponible
     */
    public function getSiguienteCodigo($cuentaPadreId = null) {
        if ($cuentaPadreId) {
            $cuentaPadre = $this->getById($cuentaPadreId);
            $prefijo = $cuentaPadre['codigo'];
            $nivel = $cuentaPadre['nivel'] + 1;
            
            $sql = "SELECT MAX(CAST(SUBSTRING(codigo, ?) AS UNSIGNED)) as max_codigo
                    FROM {$this->table}
                    WHERE codigo LIKE ? AND nivel = ?";
            
            $longitudPrefijo = strlen($prefijo) + 1;
            $patron = $prefijo . "%";
            
            $result = $this->queryOne($sql, [$longitudPrefijo, $patron, $nivel]);
            $siguienteNumero = ($result['max_codigo'] ?? 0) + 1;
            
            return $prefijo . str_pad($siguienteNumero, 2, '0', STR_PAD_LEFT);
        } else {
            // Cuenta de primer nivel
            $sql = "SELECT MAX(CAST(codigo AS UNSIGNED)) as max_codigo
                    FROM {$this->table}
                    WHERE nivel = 1";
            
            $result = $this->queryOne($sql);
            $siguienteNumero = ($result['max_codigo'] ?? 0) + 1000;
            
            return (string)$siguienteNumero;
        }
    }
}