<?php
/**
 * Manejador de vistas del sistema
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class View {
    private $data = [];
    private $layout = 'main';
    
    /**
     * Establecer datos para la vista
     */
    public function setData($key, $value = null) {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
    }
    
    /**
     * Establecer layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }
    
    /**
     * Renderizar vista
     */
    public function render($view, $data = [], $return = false) {
        // Combinar datos
        $this->data = array_merge($this->data, $data);
        
        // Extraer variables para las vistas
        extract($this->data);
        
        // Iniciar buffer de salida
        ob_start();
        
        // Incluir vista
        $viewPath = VIEWS_PATH . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("Vista $view no encontrada en $viewPath");
        }
        
        $content = ob_get_clean();
        
        if ($return) {
            return $content;
        }
        
        // Si no hay layout, mostrar directamente
        if (!$this->layout) {
            echo $content;
            return;
        }
        
        // Renderizar con layout
        $layoutPath = VIEWS_PATH . 'layouts/' . $this->layout . '.php';
        
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            // Si no existe el layout, mostrar el contenido directamente
            echo $content;
        }
    }
    
    /**
     * Renderizar vista parcial
     */
    public function partial($view, $data = []) {
        $oldLayout = $this->layout;
        $this->layout = null;
        
        $result = $this->render($view, $data, true);
        
        $this->layout = $oldLayout;
        
        return $result;
    }
    
    /**
     * Escape de HTML
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Formatear fecha
     */
    public function formatDate($date, $format = null) {
        if (!$format) {
            $format = DATE_FORMAT;
        }
        
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        return $date->format($format);
    }
    
    /**
     * Formatear fecha y hora
     */
    public function formatDateTime($datetime, $format = null) {
        if (!$format) {
            $format = DATETIME_FORMAT;
        }
        
        if (is_string($datetime)) {
            $datetime = new DateTime($datetime);
        }
        
        return $datetime->format($format);
    }
    
    /**
     * Formatear moneda
     */
    public function formatMoney($amount, $decimals = 2) {
        return CURRENCY_SYMBOL . number_format($amount, $decimals, '.', ',');
    }
    
    /**
     * Formatear número
     */
    public function formatNumber($number, $decimals = 2) {
        return number_format($number, $decimals, '.', ',');
    }
    
    /**
     * Generar URL
     */
    public function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
    
    /**
     * Generar URL de asset
     */
    public function asset($path) {
        return BASE_URL . '/public/' . ltrim($path, '/');
    }
    
    /**
     * Incluir CSS
     */
    public function css($files) {
        if (!is_array($files)) {
            $files = [$files];
        }
        
        $html = '';
        foreach ($files as $file) {
            $url = $this->asset('css/' . $file . '.css');
            $html .= '<link rel="stylesheet" href="' . $url . '">' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Incluir JavaScript
     */
    public function js($files) {
        if (!is_array($files)) {
            $files = [$files];
        }
        
        $html = '';
        foreach ($files as $file) {
            $url = $this->asset('js/' . $file . '.js');
            $html .= '<script src="' . $url . '"></script>' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Mostrar mensaje flash
     */
    public function flash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            
            $alertClass = '';
            switch ($flash['type']) {
                case 'success':
                    $alertClass = 'alert-success';
                    break;
                case 'error':
                    $alertClass = 'alert-danger';
                    break;
                case 'warning':
                    $alertClass = 'alert-warning';
                    break;
                case 'info':
                    $alertClass = 'alert-info';
                    break;
                default:
                    $alertClass = 'alert-info';
            }
            
            return '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">' .
                   htmlspecialchars($flash['message']) .
                   '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' .
                   '</div>';
        }
        
        return '';
    }
    
    /**
     * Generar token CSRF
     */
    public function csrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Campo CSRF oculto
     */
    public function csrfField() {
        return '<input type="hidden" name="csrf_token" value="' . $this->csrfToken() . '">';
    }
}