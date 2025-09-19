<?php
/**
 * Controlador de Producción
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class ProduccionController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener estadísticas de producción
        $loteModel = new LoteProduccion();
        $recetaModel = new Receta();
        
        $lotesProgramados = $loteModel->getLotesProgramados();
        $lotesEnProceso = $loteModel->getLotesEnProceso();
        $lotesEnMaduracion = $loteModel->getLotesEnMaduracion();
        $recetasActivas = $recetaModel->getRecetasConProducto();
        
        $this->view->render('modules/produccion/index', [
            'title' => 'Módulo de Producción',
            'lotes_programados' => $lotesProgramados,
            'lotes_en_proceso' => $lotesEnProceso,
            'lotes_en_maduracion' => $lotesEnMaduracion,
            'recetas_activas' => $recetasActivas
        ]);
    }
    
    public function recetas() {
        $this->requireAuth();
        
        $recetaModel = new Receta();
        $productoModel = new Producto();
        
        $recetas = $recetaModel->getRecetasConProducto();
        $productos = $productoModel->getActivos();
        
        $this->view->render('modules/produccion/recetas', [
            'title' => 'Recetas de Producción',
            'recetas' => $recetas,
            'productos' => $productos
        ]);
    }
    
    public function lotes() {
        $this->requireAuth();
        
        $loteModel = new LoteProduccion();
        $lotes = $loteModel->getLotesConDetalles();
        
        $this->view->render('modules/produccion/lotes', [
            'title' => 'Lotes de Producción',
            'lotes' => $lotes
        ]);
    }
    
    public function crearLote() {
        $this->requireAuth();
        
        $recetaModel = new Receta();
        $usuarioModel = new Model();
        $usuarioModel->table = 'usuarios';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loteModel = new LoteProduccion();
            
            $datos = [
                'numero_lote' => $loteModel->generarNumeroLote(),
                'receta_id' => $_POST['receta_id'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'cantidad_programada' => $_POST['cantidad_programada'],
                'operador_id' => $_POST['operador_id'] ?? null,
                'supervisor_id' => $_POST['supervisor_id'] ?? null,
                'estado' => 'programado',
                'observaciones' => $_POST['observaciones'] ?? null
            ];
            
            $loteId = $loteModel->create($datos);
            
            if ($loteId) {
                $_SESSION['flash_message'] = 'Lote creado exitosamente';
                $_SESSION['flash_type'] = 'success';
                header('Location: ' . BASE_URL . '/produccion/lotes/ver/' . $loteId);
                exit;
            } else {
                $_SESSION['flash_message'] = 'Error al crear el lote';
                $_SESSION['flash_type'] = 'error';
            }
        }
        
        $recetas = $recetaModel->getRecetasConProducto();
        $operadores = $usuarioModel->findBy('rol', 'operador');
        $supervisores = $usuarioModel->findBy('rol', 'supervisor');
        
        $this->view->render('modules/produccion/crear-lote', [
            'title' => 'Crear Lote de Producción',
            'recetas' => $recetas,
            'operadores' => $operadores,
            'supervisores' => $supervisores
        ]);
    }
    
    public function verLote($id) {
        $this->requireAuth();
        
        $loteModel = new LoteProduccion();
        $lote = $loteModel->getLoteCompleto($id);
        
        if (!$lote) {
            $_SESSION['flash_message'] = 'Lote no encontrado';
            $_SESSION['flash_type'] = 'error';
            header('Location: ' . BASE_URL . '/produccion/lotes');
            exit;
        }
        
        // Si es una petición POST, actualizar el lote
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            
            switch ($accion) {
                case 'iniciar_produccion':
                    $datos = [
                        'estado' => 'en_proceso',
                        'fecha_inicio' => date('Y-m-d H:i:s'),
                        'temperatura_proceso' => $_POST['temperatura_proceso'] ?? null,
                        'ph_inicial' => $_POST['ph_inicial'] ?? null,
                        'humedad_inicial' => $_POST['humedad_inicial'] ?? null
                    ];
                    $loteModel->update($id, $datos);
                    $_SESSION['flash_message'] = 'Producción iniciada';
                    $_SESSION['flash_type'] = 'success';
                    break;
                    
                case 'terminar_produccion':
                    $datos = [
                        'estado' => 'terminado',
                        'fecha_fin' => date('Y-m-d H:i:s'),
                        'cantidad_producida' => $_POST['cantidad_producida'] ?? 0,
                        'litros_leche_utilizados' => $_POST['litros_leche_utilizados'] ?? 0,
                        'ph_final' => $_POST['ph_final'] ?? null,
                        'humedad_final' => $_POST['humedad_final'] ?? null,
                        'observaciones' => $_POST['observaciones'] ?? null
                    ];
                    
                    // Calcular rendimiento real
                    if ($datos['cantidad_producida'] > 0 && $datos['litros_leche_utilizados'] > 0) {
                        $datos['rendimiento_real'] = $datos['cantidad_producida'] / $datos['litros_leche_utilizados'];
                    }
                    
                    $loteModel->update($id, $datos);
                    $_SESSION['flash_message'] = 'Producción terminada';
                    $_SESSION['flash_type'] = 'success';
                    break;
            }
            
            // Recargar datos del lote
            $lote = $loteModel->getLoteCompleto($id);
        }
        
        $this->view->render('modules/produccion/ver-lote', [
            'title' => 'Lote de Producción #' . $lote['numero_lote'],
            'lote' => $lote
        ]);
    }
    
    public function crearReceta() {
        $this->requireAuth();
        
        $productoModel = new Producto();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recetaModel = new Receta();
            
            $datos = [
                'producto_id' => $_POST['producto_id'],
                'codigo' => $_POST['codigo'],
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'] ?? null,
                'rendimiento_litros_leche' => $_POST['rendimiento_litros_leche'],
                'rendimiento_kg_queso' => $_POST['rendimiento_kg_queso'],
                'tiempo_preparacion' => $_POST['tiempo_preparacion'],
                'tiempo_maduracion' => $_POST['tiempo_maduracion'] ?? null,
                'temperatura_proceso' => $_POST['temperatura_proceso'] ?? null,
                'ph_optimo' => $_POST['ph_optimo'] ?? null,
                'humedad_maduracion' => $_POST['humedad_maduracion'] ?? null,
                'instrucciones' => $_POST['instrucciones'] ?? null,
                'version' => '1.0',
                'estado' => 'activo'
            ];
            
            $recetaId = $recetaModel->create($datos);
            
            if ($recetaId) {
                $_SESSION['flash_message'] = 'Receta creada exitosamente';
                $_SESSION['flash_type'] = 'success';
                header('Location: ' . BASE_URL . '/produccion/recetas');
                exit;
            } else {
                $_SESSION['flash_message'] = 'Error al crear la receta';
                $_SESSION['flash_type'] = 'error';
            }
        }
        
        $productos = $productoModel->getActivos();
        
        $this->view->render('modules/produccion/crear-receta', [
            'title' => 'Crear Nueva Receta',
            'productos' => $productos
        ]);
    }
}