<?php
/**
 * Modelo de Clientes
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Cliente extends Model {
    protected $table = 'clientes';
    
    /**
     * Obtener clientes activos
     */
    public function getActivos() {
        return $this->findBy('estado', 'activo');
    }
    
    /**
     * Obtener clientes por tipo
     */
    public function getByTipo($tipo) {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = ? AND estado = 'activo' ORDER BY nombre";
        return $this->query($sql, [$tipo]);
    }
    
    /**
     * Buscar clientes
     */
    public function buscar($termino) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (nombre LIKE ? OR codigo LIKE ? OR contacto LIKE ?) 
                AND estado = 'activo' 
                ORDER BY nombre";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino, $termino]);
    }
    
    /**
     * Obtener estadísticas del cliente
     */
    public function getEstadisticas($clienteId) {
        // Total de órdenes
        $sql1 = "SELECT COUNT(*) as total_ordenes, SUM(total) as total_ventas
                 FROM ordenes_venta 
                 WHERE cliente_id = ?";
        $ventas = $this->queryOne($sql1, [$clienteId]);
        
        // Última compra
        $sql2 = "SELECT fecha_orden
                 FROM ordenes_venta 
                 WHERE cliente_id = ? 
                 ORDER BY fecha_orden DESC 
                 LIMIT 1";
        $ultimaCompra = $this->queryOne($sql2, [$clienteId]);
        
        return [
            'total_ordenes' => $ventas['total_ordenes'] ?? 0,
            'total_ventas' => $ventas['total_ventas'] ?? 0,
            'ultima_compra' => $ultimaCompra['fecha_orden'] ?? null
        ];
    }
    
    /**
     * Verificar límite de crédito
     */
    public function verificarCredito($clienteId, $montoOrden) {
        $cliente = $this->getById($clienteId);
        if (!$cliente) return false;
        
        // Obtener saldo pendiente
        $sql = "SELECT SUM(saldo_pendiente) as saldo_total
                FROM ordenes_venta 
                WHERE cliente_id = ? AND estado_pago = 'pendiente'";
        $saldo = $this->queryOne($sql, [$clienteId]);
        $saldoActual = $saldo['saldo_total'] ?? 0;
        
        return ($saldoActual + $montoOrden) <= $cliente['credito_limite'];
    }
}