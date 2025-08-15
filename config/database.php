<?php
// Protección contra acceso directo
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Acceso denegado');
}

// Configuración de base de datos
return [
    'host' => 'localhost',
    'database' => 'proyecto3',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
];
?>
