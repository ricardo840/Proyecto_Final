<?php
// Iniciar sesión de manera segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de zona horaria (ajusta según tu ubicación)
date_default_timezone_set('America/Mexico_City');
?>