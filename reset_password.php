<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['reset_email'])) {
    header('Location: recover_password.php');
    exit;
}

if (isset($_POST['submit'])) {
    $new_password = $_POST['new_password'];
    $email = $_SESSION['reset_email'];

    // Validar y actualizar la nueva contraseña
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $query = $cnnPDO->prepare('UPDATE usuarios SET contrasena = :contrasena WHERE email = :email');
    $query->bindParam(':contrasena', $hashed_password);
    $query->bindParam(':email', $email);
    $query->execute();

    // Limpiar la sesión y redirigir al usuario
    unset($_SESSION['reset_email']);
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<title>Restablecer Contraseña - Tienda de Ropa</title>
<style>
   body
        {
            background-image: url(img/fondo.jpg);
            background-position: center;
            background-repeat: no-repeat; 
            background-size: cover;
            background-attachment: 15px;
        }
        body
        {
            min-height: 100vh;
            background: linear-gradient( rgba(5,7,12,0.75), rgba(5,7,12,0.20)),
            url(img/fondo.jpg) no-repeat center fixed;
            background-size: cover;
            backdrop-filter: blur(3px);   
        }
  .container 
  { 
    max-width: 600px; 
    margin: 20px auto; 
    background: #fff; 
    padding: 20px; 
    border-radius: 8px; 
    box-shadow: 0 0 10px rgba(0,0,0,0.1); 
  }
  h2 
  { 
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

<br><br><br><br><br><br><br>
<div class="container">
  <h2>Restablecer Contraseña</h2>
  <form action="" method="post">
    <div class="form-group">
      <label for="new_password">Nueva Contraseña:</label>
      <input type="password" id="new_password" name="new_password" class="form-control" required>
    </div>
    <center>
    <button type="submit" name="submit" class="btn btn-dark btn-outline-light">Restablecer Contraseña</button>
    </center>
  </form>
</div>


<br><br><br><br>
<div class="d-grid gap-2 d-md-flex justify-content-md-end">
<a href="login.php" class="btn btn-dark btn-outline-light mx-1">Regresar</a>
</div>
</body>
</html>


