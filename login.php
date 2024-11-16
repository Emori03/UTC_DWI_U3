<?php
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_secure', 1); // Solo cookies seguras
ini_set('session.cookie_httponly', 1); // Impide acceso a las cookies desde JavaScript

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'conexion.php';

// Límite de intentos de inicio de sesión
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= 5) {
    die("Demasiados intentos fallidos. Intente más tarde.");
}

if (isset($_POST['entrar'])) {
    // Validar entradas
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contrasena = $_POST['contrasena'];

    if ($email && $contrasena) {
        try {
            // Buscar usuario
            $query = $cnnPDO->prepare('SELECT id, nombre, email, contrasena, role FROM usuarios WHERE email = :email');
            $query->bindParam(':email', $email);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($contrasena, $user['contrasena'])) {
                // Regenerar ID de sesión
                session_regenerate_id(true);

                // Almacenar datos en sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8');
                $_SESSION['role'] = $user['role'];

                // Redirigir según el rol
                header('Location: dashboard.php');
                exit;
            } else {
                $_SESSION['login_attempts']++;
                $error = "Credenciales incorrectas.";
            }
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            $error = "Error interno. Por favor, intente de nuevo.";
        }
    } else {
        $error = "Por favor, ingrese email y contraseña.";
    }
}
?>

<!-- Formulario HTML para inicio de sesión -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Iniciar Sesión</title>
    <style>
        body {
            background-image: url(img/fondo.jpg);
            background-position: center;
            background-repeat: no-repeat; 
            background-size: cover;
            background-attachment: fixed;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(rgba(5,7,12,0.75), rgba(5,7,12,0.20)),
            url(img/fondo.jpg) no-repeat center fixed;
            background-size: cover;
            backdrop-filter: blur(3px);   
        }
        .container {
            max-width: 600px; 
            margin: 20px auto; 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h2 {
            text-align: center; 
            color: #333; 
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #666; }
        input[type="text"], input[type="email"], input[type="password"] { width: calc(100% - 20px); padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        input[type="submit"]:hover { background-color: #45a049; }
    </style>
</head>
<body>
<br><br><br>
<div class="container">
    <h2>Iniciar Sesión</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="contrasena" required>
        </div>
        <center>
            <div class="d-grid gap-2">
                <button type="submit" name="entrar" class="btn btn-dark btn-outline-light">Entrar</button>
            </div>
            <br><br>
            ¿No tienes cuenta?
            <a href="registro.php" class="btn btn-outline-dark mx-1">Regístrate</a>
            <br><br>
            <a href="recover_password.php" class="btn btn-outline-dark mx-1">Recuperar Contraseña</a>
        </center>
    </form>
    <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>$error</div>"; ?>
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <a href="index.php" class="btn btn-dark btn-outline-light mx-1">Regresar</a>
</div>
</body>
</html>
