<?php 
session_start();
require 'conexion.php';

if (isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    try {
        $query = $cnnPDO->prepare('SELECT * FROM usuarios WHERE email = :email');
        $query->bindParam(':email', $email);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['session_key'] = md5(uniqid(mt_rand(), true)); 

            $updateQuery = $cnnPDO->prepare('UPDATE usuarios SET session_key = :session_key WHERE id = :id');
            $updateQuery->bindParam(':session_key', $_SESSION['session_key']);
            $updateQuery->bindParam(':id', $user['id']);
            $updateQuery->execute();

            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        } else {
            $error = "Credenciales incorrectas.";
        }
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}
?>


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
