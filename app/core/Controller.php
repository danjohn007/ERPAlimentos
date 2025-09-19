<?php
/**
 * Controlador base del sistema
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Controller {
    protected $model;
    protected $view;
    
    public function __construct() {
        $this->view = new View();
    }
    
    /**
     * Cargar modelo
     */
    protected function loadModel($model) {
        $modelPath = MODELS_PATH . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        }
        throw new Exception("Modelo $model no encontrado");
    }
    
    /**
     * Verificar autenticación
     */
    protected function requireAuth() {
        if (!Auth::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Verificar rol de usuario
     */
    protected function requireRole($roles) {
        $this->requireAuth();
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        $userRole = Auth::getUserRole();
        if (!in_array($userRole, $roles)) {
            $this->view->render('errors/403', [
                'message' => 'No tiene permisos para acceder a esta sección'
            ]);
            exit;
        }
    }
    
    /**
     * Redireccionar
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }
    
    /**
     * Establecer mensaje flash
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Obtener mensaje flash
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
    
    /**
     * Validar token CSRF
     */
    protected function validateCSRF($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            throw new Exception('Token CSRF inválido');
        }
    }
    
    /**
     * Generar token CSRF
     */
    protected function generateCSRF() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
    
    /**
     * Validar datos de entrada
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = isset($data[$field]) ? trim($data[$field]) : '';
            
            // Requerido
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "El campo $field es requerido";
                continue;
            }
            
            // Email
            if (strpos($rule, 'email') !== false && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "El campo $field debe ser un email válido";
            }
            
            // Numérico
            if (strpos($rule, 'numeric') !== false && !empty($value) && !is_numeric($value)) {
                $errors[$field] = "El campo $field debe ser numérico";
            }
            
            // Longitud mínima
            if (preg_match('/min:(\d+)/', $rule, $matches) && strlen($value) < $matches[1]) {
                $errors[$field] = "El campo $field debe tener al menos {$matches[1]} caracteres";
            }
            
            // Longitud máxima
            if (preg_match('/max:(\d+)/', $rule, $matches) && strlen($value) > $matches[1]) {
                $errors[$field] = "El campo $field no puede tener más de {$matches[1]} caracteres";
            }
        }
        
        return $errors;
    }
    
    /**
     * Respuesta JSON para AJAX
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}