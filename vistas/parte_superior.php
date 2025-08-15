<?php
    // Obtener el nombre del archivo actual
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Función para verificar si la página está activa
    function isActive($page, $current_page) {
        return ($page == $current_page) ? 'active' : '';
    }
?>
<!DOCTYPE html>
<html lang="es"> 

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Equipo 5 Furiosos - Tutorias</title>

    <link href="css/nuevo_diseño.css?v=3" rel="stylesheet" id="theme-style">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
         
<body class="light-mode">
    <script>
        // Verificar preferencia guardada
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.className = savedTheme + '-mode';
                // Agregar clase para transiciones después de la carga inicial
        setTimeout(() => {
            document.body.classList.add('loaded');
        }, 100);
    </script>
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <img src="img/logo_up.png" alt="Logo Universidad" style="max-width: 50px; height: auto;">
                <div class="sidebar-brand-text mx-3"><strong>Tutorías</strong> <sup></sup></div>
            </a>
            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            
            <!-- Inicio -->
           <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i>
                    <span><strong>Inicio</strong></span>
                </a>
            </li>
            
            <!-- Navigation Items -->
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('alumnos.php', $current_page); ?>" href="alumnos.php">
                    <i class="fas fa-user-graduate"></i>
                    <span><strong>Alumnos</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('carreras.php', $current_page); ?>" href="carreras.php">
                    <i class="fas fa-book"></i>
                    <span><strong>Carreras</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('grupos.php', $current_page); ?>" href="grupos.php">
                    <i class="fas fa-users"></i>
                    <span><strong>Grupos</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('maestros.php', $current_page); ?>" href="maestros.php">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span><strong>Maestros</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('materias.php', $current_page); ?>" href="materias.php">
                    <i class="fas fa-book-open"></i>
                    <span><strong>Materias</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('asignacion.php', $current_page); ?>" href="asignacion.php">
                    <i class="fas fa-hands-helping"></i>
                    <span><strong>Tutorias</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('materias_grupos.php', $current_page); ?>" href="materias_grupos.php">
                    <i class="fas fa-layer-group"></i>
                    <span><strong>Materias por Grupo</strong></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('materias_carrera.php', $current_page); ?>" href="materias_carrera.php">
                    <i class="fas fa-project-diagram"></i>
                    <span><strong>Materias por Carrera</strong></span>
                </a>
            </li>
           
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand topbar">
                    <span class="usuario-activo">
                        <string>Usuario activo:</string> <strong><?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'usuario'; ?></strong>
                    </span>

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Botón de modo oscuro/claro -->
                        <li class="nav-item theme-toggle-container">
                            <button id="theme-toggle" class="btn btn-link">
                                <i class="fas fa-moon" id="theme-icon"></i>
                            </button>
                        </li>
                        
                        <div class="topbar-divider d-none d-sm-block"></div>    
                        
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>