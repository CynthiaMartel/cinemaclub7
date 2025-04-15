<?php
require_once(__DIR__."/../config/config.globales.php");

class SecureSessionHandler implements SessionHandlerInterface {
    private static bool $active = false;

    public function __construct() {
    }
    
    public function start(
        $name = CONFIG_SESIONES['NOMBRE_SESION_LOGIN'],
        $lifetime = 0,
        $path = '/',
        $domain = CONFIG_SESIONES['DOMINIO_SESION_LOGIN'],
        $secure = true,
        $httponly = true,
        $samesite = 'Strict'
    ): void {
        global $_SESSION;
    
        if (!self::$active) {
            // Evitar reiniciar sesión si ya está activa
            if (session_status() !== PHP_SESSION_ACTIVE) {
    
                // Establecer el nombre de la sesión
                session_name($name);
    
                // Establecer la configuración de la cookie de sesión
                session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
                ini_set('session.cookie_samesite', $samesite);
    
                // Iniciar la sesión
                session_start();
    
                // Regenerar el ID de sesión si NO es una llamada AJAX
                if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
                    session_regenerate_id(true);
                }
            }
    
            // Establecer la sesión como activa
            self::$active = true;
        }
    }
    
    
    public function open($path, $name): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): false | string {
        if (!isset($_SESSION[$id])) {
            $this->destroySession();
            return false;
        }

        return $_SESSION[$id];
    }

    public function write($id, $data): bool {
        $_SESSION[$id] = $data;
        return true;
    }

    public function destroy($id): bool {
        unset($_SESSION[$id]);
        return true;
    }

    public function destroySession(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
        }
    }
    

    public function gc($max_lifetime): false | int {
        return true;
    }
}

?>