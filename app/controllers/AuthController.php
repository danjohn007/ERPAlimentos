<?php
/**
 * Controlador de autenticación
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class AuthController extends Controller {
    
    public function login() {
        // Si ya está logueado, redirigir al dashboard
        if (Auth::isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $this->setFlash('error', 'Por favor ingrese usuario y contraseña');
            } else {
                // Modo demo si no hay base de datos
                if ($username === 'demo' && $password === 'demo') {
                    $_SESSION['user_id'] = 1;
                    $_SESSION['username'] = 'demo';
                    $_SESSION['email'] = 'demo@erpquesos.com';
                    $_SESSION['nombre'] = 'Usuario';
                    $_SESSION['apellidos'] = 'Demo';
                    $_SESSION['rol'] = 'admin';
                    $this->setFlash('success', 'Modo demo activado');
                    $this->redirect('dashboard');
                } else {
                    $result = Auth::login($username, $password);
                    
                    if ($result['success']) {
                        $this->setFlash('success', $result['message']);
                        $this->redirect('dashboard');
                    } else {
                        $this->setFlash('error', $result['message']);
                    }
                }
            }
        }
        
        $this->view->setLayout('auth');
        $this->view->render('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }
    
    public function logout() {
        Auth::logout();
        $this->setFlash('success', 'Sesión cerrada exitosamente');
        $this->redirect('login');
    }
}