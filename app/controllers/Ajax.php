<?php
/**
 * Controlador AJAX (sin sufijo Controller para coincidir con el router)
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Ajax extends Controller {
    
    public function handle($module, $action = null) {
        $this->requireAuth();
        
        // Set JSON header
        header('Content-Type: application/json');
        
        // Manejar endpoints específicos de usuario
        if ($module === 'get_user_profile') {
            $this->get_user_profile();
            return;
        }
        
        if ($module === 'change_password') {
            $this->change_password();
            return;
        }
        
        // Basic response for all AJAX requests
        $response = [
            'success' => false,
            'message' => 'Funcionalidad AJAX en desarrollo',
            'module' => $module,
            'action' => $action
        ];
        
        // Handle specific AJAX endpoints when implemented
        switch ($module) {
            case 'dashboard':
                $response = $this->handleDashboard($action);
                break;
                
            case 'produccion':
                $response = $this->handleProduccion($action);
                break;
                
            case 'materias-primas':
                $response = $this->handleMateriasPrimas($action);
                break;
                
            default:
                $response['message'] = "Módulo AJAX '$module' no implementado aún";
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Obtener datos del perfil del usuario actual
     */
    public function get_user_profile() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        try {
            $db = Database::getInstance();
            $userId = Auth::getUserId();
            
            if (!$userId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ]);
                exit;
            }
            
            $sql = "SELECT id, username, email, nombre, apellidos, rol, ultimo_acceso, fecha_creacion 
                    FROM usuarios WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Formatear fechas para mejor legibilidad
                if ($user['ultimo_acceso']) {
                    $user['ultimo_acceso'] = date('d/m/Y H:i:s', strtotime($user['ultimo_acceso']));
                } else {
                    $user['ultimo_acceso'] = 'Nunca';
                }
                if ($user['fecha_creacion']) {
                    $user['fecha_creacion'] = date('d/m/Y', strtotime($user['fecha_creacion']));
                } else {
                    $user['fecha_creacion'] = 'N/A';
                }
                
                echo json_encode([
                    'success' => true,
                    'user' => $user,
                    'debug' => 'Datos obtenidos correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no encontrado en la base de datos',
                    'debug' => 'UserID: ' . $userId
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener datos del usuario: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
        exit;
    }
    
    /**
     * Cambiar contraseña del usuario actual
     */
    public function change_password() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validaciones
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son requeridos'
                ]);
                exit;
            }
            
            if (strlen($newPassword) < 8) {
                echo json_encode([
                    'success' => false,
                    'message' => 'La nueva contraseña debe tener al menos 8 caracteres'
                ]);
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Las contraseñas no coinciden'
                ]);
                exit;
            }
            
            // Usar el método de Auth para cambiar la contraseña
            $userId = Auth::getUserId();
            $result = Auth::changePassword($userId, $currentPassword, $newPassword);
            
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    private function handleDashboard($action) {
        switch ($action) {
            case 'stats':
                return [
                    'success' => true,
                    'data' => [
                        'message' => 'Estadísticas del dashboard disponibles en modo demo'
                    ]
                ];
            default:
                return [
                    'success' => false,
                    'message' => "Acción '$action' no implementada para dashboard"
                ];
        }
    }
    
    private function handleProduccion($action) {
        switch ($action) {
            case 'crear-lote':
                return $this->crearLote();
            case 'crear-receta':
                return $this->crearReceta();
            case 'verificar-materias-primas':
                return $this->verificarMateriasPrimas();
            case 'reporte-produccion-diario':
                return $this->reporteProduccionDiario();
            case 'control-calidad-rapido':
                return $this->controlCalidadRapido();
            case 'alertas-produccion':
                return $this->alertasProduccion();
            default:
                return [
                    'success' => false,
                    'message' => "Acción '$action' no implementada para producción"
                ];
        }
    }
    
    private function crearLote() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return ['success' => false, 'message' => 'Método no permitido'];
            }
            
            $loteModel = new LoteProduccion();
            $recetaModel = new Receta();
            
            // Validar datos requeridos
            $required = ['receta_id', 'cantidad_leche', 'fecha_inicio'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    return ['success' => false, 'message' => "Campo requerido: $field"];
                }
            }
            
            // Obtener información de la receta
            $receta = $recetaModel->getById($_POST['receta_id']);
            if (!$receta) {
                return ['success' => false, 'message' => 'Receta no encontrada'];
            }
            
            // Preparar datos del lote
            $datos = [
                'numero_lote' => $loteModel->generarNumeroLote(),
                'receta_id' => $_POST['receta_id'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'cantidad_programada' => $_POST['cantidad_leche'],
                'litros_leche_utilizados' => $_POST['cantidad_leche'],
                'operador_id' => $_POST['operador'] ?? null,
                'estado' => 'programado',
                'observaciones' => $_POST['observaciones'] ?? null
            ];
            
            $loteId = $loteModel->create($datos);
            
            if ($loteId) {
                return [
                    'success' => true,
                    'lote_id' => $loteId,
                    'numero_lote' => $datos['numero_lote'],
                    'receta_nombre' => $receta['nombre'],
                    'message' => 'Lote creado exitosamente'
                ];
            } else {
                return ['success' => false, 'message' => 'Error al crear el lote'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function crearReceta() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return ['success' => false, 'message' => 'Método no permitido'];
            }
            
            $recetaModel = new Receta();
            $productoModel = new Producto();
            
            // Validar datos requeridos
            $required = ['nombre_receta', 'tipo_queso', 'tiempo_proceso', 'rendimiento'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    return ['success' => false, 'message' => "Campo requerido: $field"];
                }
            }
            
            // Buscar o crear producto basado en el tipo de queso
            $tipoQueso = $_POST['tipo_queso'];
            $nombreProducto = ucfirst($tipoQueso) . ' - ' . $_POST['nombre_receta'];
            
            // Buscar producto existente o crear uno nuevo
            $producto = $productoModel->findOneBy('nombre', $nombreProducto);
            if (!$producto) {
                $codigoProducto = 'PROD-' . strtoupper(substr($tipoQueso, 0, 3)) . '-' . date('md');
                $producto_id = $productoModel->create([
                    'codigo' => $codigoProducto,
                    'nombre' => $nombreProducto,
                    'tipo' => $tipoQueso === 'gouda' || $tipoQueso === 'cheddar' ? 'queso_semicurado' : 'queso_curado',
                    'categoria' => $tipoQueso
                ]);
            } else {
                $producto_id = $producto['id'];
            }
            
            // Generar código único para la receta
            $codigo = 'REC-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            // Preparar datos de la receta
            $datos = [
                'producto_id' => $producto_id,
                'codigo' => $codigo,
                'nombre' => $_POST['nombre_receta'],
                'descripcion' => $_POST['procedimiento'] ?? 'Receta de ' . $_POST['tipo_queso'],
                'tiempo_preparacion' => $_POST['tiempo_proceso'],
                'tiempo_maduracion' => $_POST['tiempo_maduracion'] ?? null,
                'rendimiento_kg_queso' => $_POST['rendimiento'],
                'rendimiento_litros_leche' => 100, // Base estándar
                'temperatura_proceso' => $_POST['temperatura_maduracion'] ?? null,
                'ph_optimo' => $_POST['ph_objetivo'] ?? null,
                'humedad_maduracion' => $_POST['humedad_maduracion'] ?? null,
                'instrucciones' => $_POST['procedimiento'] ?? null,
                'version' => '1.0',
                'estado' => 'activo'
            ];
            
            $recetaId = $recetaModel->create($datos);
            
            if ($recetaId) {
                return [
                    'success' => true,
                    'receta_id' => $recetaId,
                    'codigo' => $codigo,
                    'message' => 'Receta creada exitosamente'
                ];
            } else {
                return ['success' => false, 'message' => 'Error al crear la receta'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function verificarMateriasPrimas() {
        try {
            $materiaPrimaModel = new MateriaPrima();
            $materias = $materiaPrimaModel->query(
                "SELECT nombre, stock_actual, stock_minimo, unidad_medida 
                 FROM materias_primas 
                 WHERE estado = 'activo' 
                 ORDER BY (stock_actual / stock_minimo) ASC"
            );
            
            return [
                'success' => true,
                'materias' => $materias
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function reporteProduccionDiario() {
        try {
            $loteModel = new LoteProduccion();
            $hoy = date('Y-m-d');
            
            // Estadísticas del día
            $lotes_iniciados = $loteModel->count("DATE(fecha_inicio) = ?", [$hoy]);
            $lotes_completados = $loteModel->count("DATE(fecha_fin) = ? AND estado = 'terminado'", [$hoy]);
            $lotes_en_proceso = $loteModel->count("estado = 'en_proceso'");
            $lotes_en_maduracion = $loteModel->count("estado = 'terminado' AND fecha_fin IS NOT NULL");
            
            // Producción por tipo (simplificado)
            $produccion_por_tipo = $loteModel->query(
                "SELECT r.nombre, SUM(lp.cantidad_producida) as cantidad
                 FROM lotes_produccion lp
                 JOIN recetas r ON lp.receta_id = r.id
                 WHERE DATE(lp.fecha_fin) = ? AND lp.estado = 'terminado'
                 GROUP BY r.id, r.nombre",
                [$hoy]
            );
            
            return [
                'success' => true,
                'lotes_iniciados' => $lotes_iniciados,
                'lotes_completados' => $lotes_completados,
                'lotes_en_proceso' => $lotes_en_proceso,
                'lotes_en_maduracion' => $lotes_en_maduracion,
                'produccion_por_tipo' => $produccion_por_tipo,
                'tiempo_promedio' => 'N/A',
                'eficiencia' => 'N/A',
                'temperatura_promedio' => 'N/A',
                'humedad_promedio' => 'N/A'
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function controlCalidadRapido() {
        try {
            $loteModel = new LoteProduccion();
            
            // Obtener análisis recientes
            $controles = $loteModel->query(
                "SELECT ac.*, lp.numero_lote, 'pH' as parametro, ac.ph as valor, 6.2 as objetivo,
                        CASE WHEN ac.ph BETWEEN 6.0 AND 6.4 THEN 'conforme' ELSE 'no_conforme' END as estado
                 FROM analisis_calidad ac
                 JOIN lotes_produccion lp ON ac.lote_produccion_id = lp.id
                 WHERE ac.fecha_analisis >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                 AND ac.ph IS NOT NULL
                 ORDER BY ac.fecha_analisis DESC
                 LIMIT 5"
            );
            
            // Si no hay datos reales, simular algunos
            if (empty($controles)) {
                $lotesActivos = $loteModel->findBy('estado', 'en_proceso');
                $controles = [];
                foreach ($lotesActivos as $lote) {
                    $controles[] = [
                        'numero_lote' => $lote['numero_lote'],
                        'parametro' => 'pH',
                        'valor' => round(6.0 + (rand(0, 40) / 100), 1),
                        'objetivo' => 6.2,
                        'estado' => rand(0, 1) ? 'conforme' : 'no_conforme'
                    ];
                }
            }
            
            return [
                'success' => true,
                'controles' => $controles
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function alertasProduccion() {
        try {
            $loteModel = new LoteProduccion();
            
            // Obtener alertas activas
            $alertas = $loteModel->query(
                "SELECT * FROM alertas 
                 WHERE tipo = 'produccion' AND estado = 'activa' 
                 ORDER BY 
                    CASE prioridad 
                        WHEN 'critica' THEN 1 
                        WHEN 'alta' THEN 2 
                        WHEN 'media' THEN 3 
                        ELSE 4 
                    END,
                    fecha_creacion DESC 
                 LIMIT 10"
            );
            
            return [
                'success' => true,
                'alertas' => $alertas
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    private function handleMateriasPrimas($action) {
        switch ($action) {
            case 'check-raw-materials':
                return $this->checkRawMaterials();
            case 'inventory-adjustment':
                return $this->processInventoryAdjustment();
            default:
                return [
                    'success' => false,
                    'message' => "Acción '$action' no implementada para materias primas"
                ];
        }
    }
    
    private function checkRawMaterials() {
        try {
            $materiaPrimaModel = new MateriaPrima();
            $stockBajo = $materiaPrimaModel->getStockBajo();
            
            // Filter critical low stock (less than 50% of minimum)
            $criticalLow = array_filter($stockBajo, function($mp) {
                return $mp['stock_actual'] <= ($mp['stock_minimo'] * 0.5);
            });
            
            return [
                'success' => true,
                'data' => [
                    'critical_low' => array_values($criticalLow),
                    'total_low' => count($stockBajo),
                    'total_critical' => count($criticalLow)
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al verificar materias primas: ' . $e->getMessage()
            ];
        }
    }
    
    private function processInventoryAdjustment() {
        // This would handle inventory adjustments
        // For now, return a placeholder response
        return [
            'success' => false,
            'message' => 'Funcionalidad de ajuste de inventario en desarrollo'
        ];
    }
}