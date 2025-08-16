<?php 

require_once "vistas/parte_superior.php";
require_once "modelos/conexion.php";
?>
<style>
    .animar-hover {
        transition: transform 0.5s ease;
    }

    .animar-hover:hover {
        transform: translateY(-10px) scale(1.1);
    }
</style>
<!-- Contenido principal inicia -->
<body>
<div class="container text-center mt-5">
    <!-- Logo -->
    <div class="mb-4">
        <img src="img/logo_up.png" alt="Logo Universidad" class="img-fluid" style="max-width: 250px;">
    </div>
    
    <!-- Texto de bienvenida -->
    <h1 class="display-5 fw-bold text-dark"><strong>
        Bienvenido a la base de datos de la<br>
        <span class="d-block mt-3">Universidad Politécnica de la Región Ribereña</span>
    </h1></strong>

    <div class="mt-4">
        <a href="https://www.uprr.edu.mx/" target="_blank">
            <img src="img/castorup.png"
                class="img-fluid mx-auto d-block animar-hover"
                style="width: 18%"
                alt="Castor - Haz clic para visitar página externa">
        </a>
    </div>
</div>

</body>
<!-- Contenido principal termina -->
<?php
require_once "vistas/parte_inferior.php";
?>
