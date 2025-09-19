<?php
/**
 * Sistema de autenticación
 */

defined('ERP_QUESOS') or die('Acceso denegado');

class Auth {
    
    /**
     * Verificar si el usuario está logueado
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Obtener ID del usuario actual
     */
    public static function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Obtener datos del usuario actual
     */
    public static function getUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'nombre' => $_SESSION['nombre'],
            'apellidos' => $_SESSION['apellidos'],
            'rol' => $_SESSION['rol']
        ];
    }
    
    /**
     * Obtener rol del usuario actual
     */
    public static function getUserRole() {
        return isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
    }
    
    /**
     * Obtener nombre completo del usuario actual
     */
    public static function getUserName() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return $_SESSION['nombre'] . ' ' . $_SESSION['apellidos'];
    }
    
    /**
     * Verificar credenciales y realizar login
     */
    public static function login($username, $password) {
        try {
            $db = Database::getInstance();
            
            // Buscar usuario
            $sql = "SELECT * FROM usuarios WHERE (username = ? OR email = ?) AND estado = 'activo'";
            $stmt = $db->prepare($sql);
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'message' => 'Usuario no encontrado o inactivo'];
            }
            
            // Verificar contraseña
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Contraseña incorrecta'];
            }
            
            // Crear sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['apellidos'] = $user['apellidos'];
            $_SESSION['rol'] = $user['rol'];
            
            // Actualizar último acceso
            $updateSql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->execute([$user['id']]);
            
            return ['success' => true, 'message' => 'Inicio de sesión exitoso'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }
    
    /**
     * Cerrar sesión
     */
    public static function logout() {
        // Limpiar variables de sesión
        $_SESSION = [];
        
        // Destruir cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destruir sesión
        session_destroy();
    }
    
    /**
     * Verificar si el usuario tiene un rol específico
     */
    public static function hasRole($role) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        return $_SESSION['rol'] === $role;
    }
    
    /**
     * Verificar si el usuario tiene uno de los roles especificados
     */
    public static function hasAnyRole($roles) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        return in_array($_SESSION['rol'], $roles);
    }
    
    /**
     * Crear nuevo usuario
     */
    public static function createUser($userData) {
        $db = Database::getInstance();
        
        // Verificar si el usuario ya existe
        $checkSql = "SELECT id FROM usuarios WHERE username = ? OR email = ?";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([$userData['username'], $userData['email']]);
        
        if ($checkStmt->fetch()) {
            return ['success' => false, 'message' => 'El usuario o email ya existe'];
        }
        
        // Hash de la contraseña
        $userData['password'] = password_hash($userData['password'], HASH_ALGORITHM);
        
        // Insertar usuario
        $sql = "INSERT INTO usuarios (username, email, password, nombre, apellidos, rol, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['nombre'],
            $userData['apellidos'],
            $userData['rol'] ?? 'operador',
            $userData['estado'] ?? 'activo'
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Usuario creado exitosamente', 'id' => $db->lastInsertId()];
        } else {
            return ['success' => false, 'message' => 'Error al crear el usuario'];
        }
    }
    
    /**
     * Cambiar contraseña
     */
    public static function changePassword($userId, $currentPassword, $newPassword) {
        $db = Database::getInstance();
        
        // Obtener usuario actual
        $sql = "SELECT password FROM usuarios WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
        
        // Verificar contraseña actual
        if (!password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
        }
        
        // Actualizar contraseña
        $newPasswordHash = password_hash($newPassword, HASH_ALGORITHM);
        $updateSql = "UPDATE usuarios SET password = ? WHERE id = ?";
        $updateStmt = $db->prepare($updateSql);
        $result = $updateStmt->execute([$newPasswordHash, $userId]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Contraseña actualizada exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar la contraseña'];
        }
    }
}