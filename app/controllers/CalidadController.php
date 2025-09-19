<?php
/**
 * Controlador de Control de Calidad
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class CalidadController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $analisisModel = new AnalisisCalidad();
        $loteModel = new LoteProduccion();
        $inventarioModel = new Inventario();
        
        $analisisRecientes = $analisisModel->getAnalisisRecientes(10);
        $analisisNoConformes = $analisisModel->getAnalisisNoConformes();
        $estadisticas = $analisisModel->getEstadisticasCalidad();
        
        $lotesEnProceso = $loteModel->getLotesEnProceso();
        $productosVencer = $inventarioModel->getProximosVencer(7);
        
        $this->view->render('modules/calidad/index', [
            'title' => 'Módulo de Control de Calidad',
            'analisis_recientes' => $analisisRecientes,
            'analisis_no_conformes' => $analisisNoConformes,
            'estadisticas' => $estadisticas,
            'lotes_en_proceso' => $lotesEnProceso,
            'productos_vencer' => $productosVencer
        ]);
    }
    
    public function analisis() {
        $this->requireAuth();
        
        $analisisModel = new AnalisisCalidad();
        $loteModel = new LoteProduccion();
        $materiaPrimaModel = new MateriaPrima();
        $productoModel = new Producto();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarNuevoAnalisis();
            return;
        }
        
        $analisisTodos = $analisisModel->getAnalisisRecientes(50);
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
    
    public function nuevo_analisis() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarNuevoAnalisis();
            return;
        }
        
        $loteModel = new LoteProduccion();
        $materiaPrimaModel = new MateriaPrima();
        $productoModel = new Producto();
        
        $lotesDisponibles = $loteModel->getLotesEnProceso();
        $lotesTerminados = $loteModel->findBy('estado', 'terminado');
        $materiasPrimas = $materiaPrimaModel->getActivos();
        $productos = $productoModel->getActivos();
        
        $this->view->render('modules/calidad/nuevo_analisis', [
            'title' => 'Nuevo Análisis de Calidad',
            'lotes_disponibles' => $lotesDisponibles,
            'lotes_terminados' => $lotesTerminados,
            'materias_primas' => $materiasPrimas,
            'productos' => $productos
        ]);
    }
    
    public function ver_analisis($id) {
        $this->requireAuth();
        
        $analisisModel = new AnalisisCalidad();
        $analisis = $analisisModel->getById($id);
        
        if (!$analisis) {
            header('Location: /calidad/analisis');
            exit;
        }
        
        // Obtener información adicional según el tipo
        $itemInfo = $this->obtenerInfoItem($analisis['tipo'], $analisis['item_id']);
        $loteInfo = null;
        
        if ($analisis['lote_produccion_id']) {
            $loteModel = new LoteProduccion();
            $loteInfo = $loteModel->getById($analisis['lote_produccion_id']);
        }
        
        $this->view->render('modules/calidad/ver_analisis', [
            'title' => 'Análisis de Calidad #' . $analisis['id'],
            'analisis' => $analisis,
            'item_info' => $itemInfo,
            'lote_info' => $loteInfo
        ]);
    }
    
    public function trazabilidad() {
        $this->requireAuth();
        
        $busqueda = $_GET['busqueda'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        
        $resultados = [];
        
        if ($busqueda) {
            $resultados = $this->buscarTrazabilidad($busqueda, $tipo);
        }
        
        $this->view->render('modules/calidad/trazabilidad', [
            'title' => 'Trazabilidad',
            'busqueda' => $busqueda,
            'tipo' => $tipo,
            'resultados' => $resultados
        ]);
    }
    
    public function no_conformidades() {
        $this->requireAuth();
        
        $analisisModel = new AnalisisCalidad();
        $noConformidades = $analisisModel->getAnalisisNoConformes();
        
        $this->view->render('modules/calidad/no_conformidades', [
            'title' => 'No Conformidades',
            'no_conformidades' => $noConformidades
        ]);
    }
    
    public function estadisticas() {
        $this->requireAuth();
        
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-t');
        
        $analisisModel = new AnalisisCalidad();
        $estadisticas = $analisisModel->getEstadisticasCalidad($fechaInicio, $fechaFin);
        
        // Obtener estadísticas por tipo
        $estadisticasPorTipo = [];
        $tipos = ['materia_prima', 'producto_terminado', 'proceso'];
        
        foreach ($tipos as $tipo) {
            $analisisTipo = $analisisModel->getAnalisisPorTipo($tipo);
            $estadisticasPorTipo[$tipo] = count($analisisTipo);
        }
        
        $this->view->render('modules/calidad/estadisticas', [
            'title' => 'Estadísticas de Calidad',
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estadisticas' => $estadisticas,
            'estadisticas_por_tipo' => $estadisticasPorTipo
        ]);
    }
    
    private function procesarNuevoAnalisis() {
        try {
            $analisisModel = new AnalisisCalidad();
            
            $datos = [
                'tipo' => $_POST['tipo'],
                'item_id' => $_POST['item_id'],
                'lote_produccion_id' => $_POST['lote_produccion_id'] ?? null,
                'fecha_analisis' => $_POST['fecha_analisis'] . ' ' . date('H:i:s'),
                'analista_id' => $_SESSION['user_id'],
                'ph' => $_POST['ph'] ?? null,
                'humedad' => $_POST['humedad'] ?? null,
                'temperatura' => $_POST['temperatura'] ?? null,
                'grasa' => $_POST['grasa'] ?? null,
                'proteina' => $_POST['proteina'] ?? null,
                'sal' => $_POST['sal'] ?? null,
                'microbiologia' => $_POST['microbiologia'] ?? '',
                'observaciones' => $_POST['observaciones'] ?? '',
                'resultado' => $_POST['resultado']
            ];
            
            $analisisId = $analisisModel->registrarAnalisis($datos);
            
            $_SESSION['success'] = 'Análisis de calidad registrado exitosamente';
            header('Location: /calidad/ver_analisis/' . $analisisId);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al registrar el análisis: ' . $e->getMessage();
            header('Location: /calidad/nuevo_analisis');
            exit;
        }
    }
    
    private function obtenerInfoItem($tipo, $itemId) {
        switch ($tipo) {
            case 'materia_prima':
                $model = new MateriaPrima();
                return $model->getById($itemId);
            case 'producto_terminado':
                $model = new Producto();
                return $model->getById($itemId);
            case 'proceso':
                $model = new LoteProduccion();
                return $model->getById($itemId);
            default:
                return null;
        }
    }
    
    private function buscarTrazabilidad($busqueda, $tipo) {
        $resultados = [];
        
        if ($tipo === 'lote' || empty($tipo)) {
            $loteModel = new LoteProduccion();
            $lotes = $loteModel->query(
                "SELECT * FROM lotes_produccion WHERE numero_lote LIKE ?",
                ["%$busqueda%"]
            );
            
            foreach ($lotes as $lote) {
                $resultados[] = [
                    'tipo' => 'Lote de Producción',
                    'identificacion' => $lote['numero_lote'],
                    'fecha' => $lote['fecha_inicio'],
                    'estado' => $lote['estado'],
                    'detalles' => $lote
                ];
            }
        }
        
        if ($tipo === 'producto' || empty($tipo)) {
            $inventarioModel = new Inventario();
            $inventarios = $inventarioModel->query(
                "SELECT i.*, p.nombre as producto_nombre 
                 FROM inventario i 
                 INNER JOIN productos p ON i.item_id = p.id 
                 WHERE i.tipo = 'producto_terminado' AND (i.lote LIKE ? OR p.nombre LIKE ?)",
                ["%$busqueda%", "%$busqueda%"]
            );
            
            foreach ($inventarios as $inv) {
                $resultados[] = [
                    'tipo' => 'Producto en Inventario',
                    'identificacion' => $inv['lote'],
                    'fecha' => $inv['fecha_entrada'],
                    'estado' => $inv['estado'],
                    'detalles' => $inv
                ];
            }
        }
        
        return $resultados;
    }
}