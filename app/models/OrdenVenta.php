<?php
/**
 * Modelo de Órdenes de Venta
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class OrdenVenta extends Model {
    protected $table = 'ordenes_venta';
    
    /**
     * Obtener órdenes con información del cliente
     */
    public function getOrdenesConCliente() {
        $sql = "SELECT ov.*, c.nombre as cliente_nombre, c.tipo as cliente_tipo
                FROM {$this->table} ov
                INNER JOIN clientes c ON ov.cliente_id = c.id
                ORDER BY ov.fecha_orden DESC";
        return $this->query($sql);
    }
    
    /**
     * Obtener orden completa con detalles
     */
    public function getOrdenCompleta($id) {
        $sql = "SELECT ov.*, c.nombre as cliente_nombre, c.contacto, c.direccion,
                       c.descuento_porcentaje, c.credito_limite
                FROM {$this->table} ov
                INNER JOIN clientes c ON ov.cliente_id = c.id
                WHERE ov.id = ?";
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Obtener detalles de la orden
     */
    public function getDetallesOrden($ordenId) {
        $sql = "SELECT ovd.*, p.nombre as producto_nombre, p.codigo as producto_codigo,
                       p.unidad_medida, p.precio_venta
                FROM orden_venta_detalles ovd
                INNER JOIN productos p ON ovd.producto_id = p.id
                WHERE ovd.orden_venta_id = ?
                ORDER BY ovd.id";
        return $this->query($sql, [$ordenId]);
    }
    
    /**
     * Generar número de orden
     */
    public function generarNumeroOrden() {
        $fecha = date('Ymd');
        $sql = "SELECT COUNT(*) + 1 as siguiente FROM {$this->table} WHERE numero_orden LIKE ?";
        $result = $this->queryOne($sql, ["ORD-$fecha-%"]);
        $secuencia = str_pad($result['siguiente'], 4, '0', STR_PAD_LEFT);
        return "ORD-$fecha-$secuencia";
    }
    
    /**
     * Obtener órdenes pendientes
     */
    public function getOrdenesPendientes() {
        return $this->findBy('estado', 'pendiente');
    }
    
    /**
     * Obtener órdenes por estado
     */
    public function getByEstado($estado) {
        $sql = "SELECT ov.*, c.nombre as cliente_nombre
                FROM {$this->table} ov
                INNER JOIN clientes c ON ov.cliente_id = c.id
                WHERE ov.estado = ?
                ORDER BY ov.fecha_orden DESC";
        return $this->query($sql, [$estado]);
    }
    
    /**
     * Buscar órdenes
     */
    public function buscar($termino) {
        $sql = "SELECT ov.*, c.nombre as cliente_nombre
                FROM {$this->table} ov
                INNER JOIN clientes c ON ov.cliente_id = c.id
                WHERE (ov.numero_orden LIKE ? OR c.nombre LIKE ?)
                ORDER BY ov.fecha_orden DESC";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino]);
    }
    
    /**
     * Actualizar estado de orden
     */
    public function actualizarEstado($id, $estado, $observaciones = null) {
        $data = ['estado' => $estado];
        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }
        return $this->update($id, $data);
    }
}