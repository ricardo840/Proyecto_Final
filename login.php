<?php
session_start();
require_once "modelos/conexion.php";

$msg = '';
$conn = (new Conexion())->conectar();

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)) {
        $msg = "Email y contraseña son obligatorios!";
    } else {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if(password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_type'] = $user['tipo_usuario'];
                    $_SESSION['nombre'] = $user['nombre']; // Guardamos el nombre
                    
                    if($user['tipo_usuario'] == 'admin') {
                        header('Location: index.php'); //entrada del admin
                    } else {
                        header('Location: index.php'); //entrada del usuario
                    }
                    exit();
                } else {
                    $msg = "Credenciales incorrectas!";
                }
            } else {
                $msg = "Credenciales incorrectas!";
            }
        } catch(PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            $msg = "Error en el sistema. Por favor intente más tarde.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/registro.css">

</head>
<body>
    <div class="form">
        <form action="" method="post">
            <h2>Iniciar Sesión</h2>
            <?php if(!empty($msg)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
           
            <div class="form-group">
                <input type="email" name="email" placeholder="Ingresa tu email" class="form-control" required>
            </div>
           
            <div class="form-group">
                <input type="password" name="password" placeholder="Ingresa tu contraseña" class="form-control" required>
            </div>
           
            <button type="submit" class="btn font-weight-bold" name="submit">Iniciar sesión</button>
            <p>No tienes una cuenta? <a href="registro.php">Registrarse</a></p>
        </form>
    </div>
</body>
</html>