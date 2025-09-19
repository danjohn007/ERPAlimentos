<?php
/**
 * Controlador de Control de Calidad
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class CalidadController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        // Necesitamos crear el modelo de análisis de calidad
        $model = new Model();
        $model->table = 'analisis_calidad';
        
        $loteModel = new LoteProduccion();
        $inventarioModel = new Inventario();
        
        $analisisRecientes = $model->query(
            "SELECT ac.*, 
                    CASE 
                        WHEN ac.tipo = 'materia_prima' THEN mp.nombre
                        WHEN ac.tipo = 'producto_terminado' THEN p.nombre
                        WHEN ac.tipo = 'proceso' THEN CONCAT('Lote ', lp.numero_lote)
                    END as item_nombre,
                    lp.numero_lote,
                    u.nombre as analista_nombre
             FROM analisis_calidad ac
             LEFT JOIN materias_primas mp ON ac.tipo = 'materia_prima' AND ac.item_id = mp.id
             LEFT JOIN productos p ON ac.tipo = 'producto_terminado' AND ac.item_id = p.id
             LEFT JOIN lotes_produccion lp ON ac.lote_produccion_id = lp.id
             LEFT JOIN usuarios u ON ac.analista_id = u.id
             ORDER BY ac.fecha_analisis DESC 
             LIMIT 10"
        );
        
        $lotesEnProceso = $loteModel->getLotesEnProceso();
        $productosVencer = $inventarioModel->getProximosVencer(7);
        
        // Contar análisis por resultado
        $estadisticas = $model->query(
            "SELECT resultado, COUNT(*) as total 
             FROM analisis_calidad 
             WHERE fecha_analisis >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY resultado"
        );
        
        $this->view->render('modules/calidad/index', [
            'title' => 'Módulo de Control de Calidad',
            'analisis_recientes' => $analisisRecientes,
            'lotes_en_proceso' => $lotesEnProceso,
            'productos_vencer' => $productosVencer,
            'estadisticas' => $estadisticas
        ]);
    }
    
    public function analisis() {
        $this->requireAuth();
        
        $model = new Model();
        $model->table = 'analisis_calidad';
        
        $loteModel = new LoteProduccion();
        $materiaPrimaModel = new MateriaPrima();
        $productoModel = new Producto();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'tipo' => $_POST['tipo'],
                'item_id' => $_POST['item_id'],
                'lote_produccion_id' => $_POST['lote_produccion_id'] ?? null,
                'fecha_analisis' => $_POST['fecha_analisis'],
                'analista_id' => $_SESSION['user_id'],
                'ph' => $_POST['ph'] ?? null,
                'humedad' => $_POST['humedad'] ?? null,
                'temperatura' => $_POST['temperatura'] ?? null,
                'grasa' => $_POST['grasa'] ?? null,
                'proteina' => $_POST['proteina'] ?? null,
                'sal' => $_POST['sal'] ?? null,
                'microbiologia' => $_POST['microbiologia'] ?? null,
                'observaciones' => $_POST['observaciones'] ?? null,
                'resultado' => $_POST['resultado']
            ];
            
            $analisisId = $model->create($datos);
            
            if ($analisisId) {
                $_SESSION['flash_message'] = 'Análisis registrado exitosamente';
                $_SESSION['flash_type'] = 'success';
            } else {
                $_SESSION['flash_message'] = 'Error al registrar el análisis';
                $_SESSION['flash_type'] = 'error';
            }
            
            header('Location: ' . BASE_URL . '/calidad/analisis');
            exit;
        }
        
        $analisisTodos = $model->query(
            "SELECT ac.*, 
                    CASE 
                        WHEN ac.tipo = 'materia_prima' THEN mp.nombre
                        WHEN ac.tipo = 'producto_terminado' THEN p.nombre
                        WHEN ac.tipo = 'proceso' THEN CONCAT('Lote ', lp.numero_lote)
                    END as item_nombre,
                    lp.numero_lote,
                    u.nombre as analista_nombre
             FROM analisis_calidad ac
             LEFT JOIN materias_primas mp ON ac.tipo = 'materia_prima' AND ac.item_id = mp.id
             LEFT JOIN productos p ON ac.tipo = 'producto_terminado' AND ac.item_id = p.id
             LEFT JOIN lotes_produccion lp ON ac.lote_produccion_id = lp.id
             LEFT JOIN usuarios u ON ac.analista_id = u.id
             ORDER BY ac.fecha_analisis DESC"
        );
        
        $lotesTerminados = $loteModel->findBy('estado', 'terminado');
        $materiasPrimas = $materiaPrimaModel->getActivos();
        $productos = $productoModel->getActivos();
        
        $this->view->render('modules/calidad/analisis', [
            'title' => 'Análisis de Calidad',
            'analisis_todos' => $analisisTodos,
            'lotes_terminados' => $lotesTerminados,
            'materias_primas' => $materiasPrimas,
            'productos' => $productos
        ]);
    }
    
    public function trazabilidad() {
        $this->requireAuth();
        
        $this->view->render('modules/calidad/trazabilidad', [
            'title' => 'Trazabilidad',
            'message' => 'Sistema de trazabilidad en desarrollo.'
        ]);
    }
}