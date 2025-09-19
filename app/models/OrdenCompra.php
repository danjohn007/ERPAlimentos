<?php
/**
 * Modelo de Órdenes de Compra
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class OrdenCompra extends Model {
    protected $table = 'ordenes_compra';
    
    /**
     * Obtener órdenes con información del proveedor
     */
    public function getOrdenesConProveedor() {
        $sql = "SELECT oc.*, p.nombre as proveedor_nombre, p.tipo as proveedor_tipo,
                       u.nombre as comprador_nombre
                FROM {$this->table} oc
                INNER JOIN proveedores p ON oc.proveedor_id = p.id
                LEFT JOIN usuarios u ON oc.comprador_id = u.id
                ORDER BY oc.fecha_orden DESC";
        return $this->query($sql);
    }
    
    /**
     * Obtener orden completa con detalles
     */
    public function getOrdenCompleta($id) {
        $sql = "SELECT oc.*, p.nombre as proveedor_nombre, p.contacto, p.direccion,
                       p.telefono, p.email, u.nombre as comprador_nombre
                FROM {$this->table} oc
                INNER JOIN proveedores p ON oc.proveedor_id = p.id
                LEFT JOIN usuarios u ON oc.comprador_id = u.id
                WHERE oc.id = ?";
        return $this->queryOne($sql, [$id]);
    }
    
    /**
     * Obtener detalles de la orden
     */
    public function getDetallesOrden($ordenId) {
        $sql = "SELECT ocd.*, mp.nombre as materia_prima_nombre, mp.codigo as materia_prima_codigo,
                       mp.unidad_medida, mp.costo_unitario
                FROM orden_compra_detalles ocd
                INNER JOIN materias_primas mp ON ocd.materia_prima_id = mp.id
                WHERE ocd.orden_compra_id = ?
                ORDER BY ocd.id";
        return $this->query($sql, [$ordenId]);
    }
    
    /**
     * Obtener órdenes por estado
     */
    public function getByEstado($estado) {
        $sql = "SELECT oc.*, p.nombre as proveedor_nombre
                FROM {$this->table} oc
                INNER JOIN proveedores p ON oc.proveedor_id = p.id
                WHERE oc.estado = ?
                ORDER BY oc.fecha_orden DESC";
        return $this->query($sql, [$estado]);
    }
    
    /**
     * Generar número de orden
     */
    public function generarNumeroOrden() {
        $fecha = date('Ymd');
        $sql = "SELECT COUNT(*) + 1 as siguiente FROM {$this->table} WHERE numero_orden LIKE ?";
        $result = $this->queryOne($sql, ["OC-$fecha-%"]);
        $secuencia = str_pad($result['siguiente'], 3, '0', STR_PAD_LEFT);
        return "OC-$fecha-$secuencia";
    }
    
    /**
     * Actualizar estado de la orden
     */
    public function actualizarEstado($id, $estado, $observaciones = null) {
        $data = ['estado' => $estado];
        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }
        return $this->update($id, $data);
    }
    
    /**
     * Buscar órdenes
     */
    public function buscar($termino) {
        $sql = "SELECT oc.*, p.nombre as proveedor_nombre
                FROM {$this->table} oc
                INNER JOIN proveedores p ON oc.proveedor_id = p.id
                WHERE (oc.numero_orden LIKE ? OR p.nombre LIKE ?)
                ORDER BY oc.fecha_orden DESC";
        $termino = "%$termino%";
        return $this->query($sql, [$termino, $termino]);
    }
    
    /**
     * Obtener órdenes pendientes de recepción
     */
    public function getOrdenesPendientesRecepcion() {
        return $this->getByEstado('confirmado');
    }
    
    /**
     * Calcular total de detalles
     */
    public function calcularTotalDetalles($ordenId) {
        $detalles = $this->getDetallesOrden($ordenId);
        $subtotal = 0;
        foreach ($detalles as $detalle) {
            $subtotal += $detalle['subtotal'];
        }
        
        $impuestos = $subtotal * 0.16; // IVA 16%
        $total = $subtotal + $impuestos;
        
        return [
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'total' => $total
        ];
    }
}