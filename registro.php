<?php
require_once "modelos/conexion.php";

$msg = '';
$conn = (new Conexion())->conectar();

if(isset($_POST['submit'])){
    // Validar que todos los campos existan
    $required_fields = ['nombre', 'email', 'password', 'cpassword', 'tipo_usuario'];
    foreach($required_fields as $field) {
        if(empty($_POST[$field])) {
            $msg = "Todos los campos son obligatorios!";
            break;
        }
    }

    if(empty($msg)) {
        $name = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $tipo_usuario = $_POST['tipo_usuario'];

        // Validar coincidencia de contraseñas
        if($password !== $cpassword) {
            $msg = "Las contraseñas no coinciden!";
        } else {
            try {
                // Verificar si el email ya existe
                $sql = "SELECT id FROM usuarios WHERE email = :email";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                
                if($stmt->rowCount() > 0) {
                    $msg = "El email ya está registrado!";
                } else {
                    // Hash de la contraseña
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insertar nuevo usuario
                    $sql = "INSERT INTO usuarios (nombre, email, password, tipo_usuario) 
                            VALUES (:nombre, :email, :password, :tipo_usuario)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':nombre', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password_hash);
                    $stmt->bindParam(':tipo_usuario', $tipo_usuario);
                    
                    if($stmt->execute()) {
                        header('Location: login.php');
                        exit();
                    } else {
                        $msg = "Error al registrar el usuario!";
                    }
                }
            } catch(PDOException $e) {
                error_log("Error en registro: " . $e->getMessage());
                $msg = "Error en el sistema. Por favor intente más tarde.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="css/registro.css" rel="stylesheet" id="theme-style">
    <link rel="stylesheet" href="sb-admin2.min.css">
</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Registro</h2>
            <?php if(!empty($msg)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <input type="text" name="nombre" placeholder="Ingresa tu nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Ingresa tu email" class="form-control" required>
            </div>
            <div class="form-group">
                <select name="tipo_usuario" class="form-control" required>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Ingresa tu contraseña" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" name="cpassword" placeholder="Confirma tu contraseña" class="form-control" required>
            </div>
            <button type="submit" class="btn font-weight-bold" name="submit">Registrar ahora</button>
            <p>Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
        </form>
    </div>
</body>
</html>


