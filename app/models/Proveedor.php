<?php
/**
 * Modelo de Proveedores
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Proveedor extends Model {
    protected $table = 'proveedores';
    
    /**
     * Obtener proveedores activos
     */
    public function getActivos() {
        return $this->findBy('estado', 'activo');
    }
    
    /**
     * Obtener proveedores por tipo
     */
    public function getByTipo($tipo) {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = ? AND estado = 'activo' ORDER BY nombre";
        return $this->query($sql, [$tipo]);
    }
    
    /**
     * Obtener proveedores certificados
     */
    public function getCertificados() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE certificaciones IS NOT NULL 
                AND certificaciones != '' 
                AND estado = 'activo' 
                ORDER BY nombre";
        return $this->query($sql);
    }
    
    /**
     * Buscar proveedores
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
     * Obtener estadísticas del proveedor
     */
    public function getEstadisticas($proveedorId) {
        // Número de materias primas suministradas
        $sql1 = "SELECT COUNT(*) as total_materias_primas 
                 FROM materias_primas 
                 WHERE proveedor_principal_id = ? AND estado = 'activo'";
        $materiasPrimas = $this->queryOne($sql1, [$proveedorId]);
        
        // Valor total de inventario del proveedor
        $sql2 = "SELECT SUM(i.costo_total) as valor_inventario
                 FROM inventario i
                 WHERE i.proveedor_id = ? AND i.estado = 'disponible'";
        $valorInventario = $this->queryOne($sql2, [$proveedorId]);
        
        return [
            'materias_primas' => $materiasPrimas['total_materias_primas'],
            'valor_inventario' => $valorInventario['valor_inventario'] ?? 0
        ];
    }
    
    /**
     * Validar certificaciones
     */
    public function validarCertificaciones($proveedorId) {
        $proveedor = $this->getById($proveedorId);
        if (!$proveedor || !$proveedor['certificaciones']) {
            return false;
        }
        
        $certificaciones = explode(',', $proveedor['certificaciones']);
        $certificacionesValidas = ['HACCP', 'TIF', 'ISO 9001', 'ISO 22000', 'FSC', 'Biodegradable'];
        
        foreach ($certificaciones as $cert) {
            if (in_array(trim($cert), $certificacionesValidas)) {
                return true;
            }
        }
        
        return false;
    }
}