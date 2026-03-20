<?php
/**
 * Logout - Cierre de sesion
 * Inventio Max v9.0
 */

// Iniciar sesion si no esta iniciada
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Eliminar sesion de usuario
if(isset($_SESSION['user_id'])){
    unset($_SESSION['user_id']);
}

// Eliminar sesion de cliente
if(isset($_SESSION['client_id'])){
    unset($_SESSION['client_id']);
}

// Destruir todas las variables de sesion
$_SESSION = array();

// Destruir la cookie de sesion
if(ini_get("session.use_cookies")){
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesion
session_destroy();

// Redirigir al inicio
header("Location: ./");
exit;
?>
