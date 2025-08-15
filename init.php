<?php
// Protección contra acceso directo
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Acceso denegado');
}

// Iniciar sesión de manera segura
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400, // 1 día
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Verificación de autenticación ESTricta
if (empty($_SESSION['user_id'])) {
    // Guarda la URL solicitada para redirigir después del login
    if (!isset($_SESSION['redirect_url']) && !in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'registro.php'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    }

    // Redirección ABSOLUTA (evita problemas con subdirectorios)
    $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $login_url = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/login.php';
    header("Location: $login_url");
    exit();
}

// Verificación de seguridad adicional (opcional pero recomendado)
if (isset($_SESSION['ip']) && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Configuración de zona horaria (ajusta según tu ubicación)
date_default_timezone_set('America/Mexico_City');

// Headers de seguridad adicionales
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
?>