<?php
/**
 * Router del sistema
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Router {
    private $routes = [];
    
    /**
     * Agregar ruta
     */
    public function addRoute($pattern, $controller, $action, $middleware = []) {
        $this->routes[] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }
    
    /**
     * Manejar solicitud
     */
    /**
     * Manejar solicitud
     */
    public function handleRequest($url) {
        // Limpiar URL
        $url = trim($url, '/');
        
        // Buscar coincidencia en las rutas
        foreach ($this->routes as $route) {
            $pattern = $route['pattern'];
            
            // Convertir patrón a expresión regular
            if (strpos($pattern, '(') !== false) {
                // El patrón ya contiene grupos de captura, usarlo directamente
                $regex = '#^' . $pattern . '$#';
            } else {
                // Patrón simple, aplicar quote
                $regex = '#^' . preg_quote($pattern, '#') . '$#';
            }
            
            if (preg_match($regex, $url, $matches)) {
                // Remover la coincidencia completa
                array_shift($matches);
                
                // Ejecutar middleware si existe
                foreach ($route['middleware'] as $middleware) {
                    if (!$this->runMiddleware($middleware)) {
                        return;
                    }
                }
                
                // Ejecutar controlador y acción
                $this->runController($route['controller'], $route['action'], $matches);
                return;
            }
        }
        
        // Si no se encuentra la ruta, mostrar 404
        $this->show404();
    }
    
    /**
     * Ejecutar controlador
     */
    private function runController($controllerName, $action, $params = []) {
        $controllerClass = $controllerName . 'Controller';
        $controllerPath = CONTROLLERS_PATH . $controllerClass . '.php';
        
        if (!file_exists($controllerPath)) {
            throw new Exception("Controlador $controllerClass no encontrado");
        }
        
        require_once $controllerPath;
        
        if (!class_exists($controllerClass)) {
            throw new Exception("Clase $controllerClass no encontrada");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $action)) {
            throw new Exception("Método $action no encontrado en $controllerClass");
        }
        
        // Ejecutar acción con parámetros
        call_user_func_array([$controller, $action], $params);
    }
    
    /**
     * Ejecutar middleware
     */
    private function runMiddleware($middleware) {
        switch ($middleware) {
            case 'auth':
                if (!Auth::isLoggedIn()) {
                    header('Location: ' . BASE_URL . '/login');
                    exit;
                }
                break;
                
            case 'admin':
                if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'admin') {
                    $this->show403();
                    return false;
                }
                break;
                
            case 'supervisor':
                if (!Auth::isLoggedIn() || !in_array(Auth::getUserRole(), ['admin', 'supervisor'])) {
                    $this->show403();
                    return false;
                }
                break;
        }
        
        return true;
    }
    
    /**
     * Mostrar error 404
     */
    private function show404() {
        http_response_code(404);
        $view = new View();
        $view->setLayout('error');
        $view->render('errors/404', [
            'title' => 'Página no encontrada',
            'message' => 'La página solicitada no existe.'
        ]);
    }
    
    /**
     * Mostrar error 403
     */
    private function show403() {
        http_response_code(403);
        $view = new View();
        $view->setLayout('error');
        $view->render('errors/403', [
            'title' => 'Acceso denegado',
            'message' => 'No tiene permisos para acceder a esta página.'
        ]);
    }
}