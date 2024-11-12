<?php
require_once 'conexion.php';
session_start();

if (isset($_POST['guardar'])) {  
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);  // Encriptar la contraseña
    $role = 'user';  // Asignar el rol de usuario por defecto
    $secret_question = $_POST['secret_question'];
    $secret_answer = password_hash($_POST['secret_answer'], PASSWORD_DEFAULT);

    $sql = $cnnPDO->prepare("INSERT INTO usuarios (nombre, email, contrasena, role, secret_question, secret_answer) VALUES (:nombre, :email, :contrasena, :role, :secret_question, :secret_answer)");
    
    $sql->bindParam(':nombre', $nombre);
    $sql->bindParam(':email', $email);
    $sql->bindParam(':contrasena', $contrasena);
    $sql->bindParam(':role', $role);
    $sql->bindParam(':secret_question', $secret_question);
    $sql->bindParam(':secret_answer', $secret_answer);

    $sql->execute();
    
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<title>Registro - Tienda de Ropa</title>
<style>
  body {
    background-image: url(img/fondo.jpg);
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: 15px;
  }
  body {
    min-height: 100vh;
    background: linear-gradient(rgba(5, 7, 12, 0.75), rgba(5, 7, 12, 0.20)), url(img/fondo.jpg) no-repeat center fixed;
    background-size: cover;
    backdrop-filter: blur(3px);
  }
  .container {
    max-width: 600px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  h2 {
    text-align: center;
    color: #333;
  }
  .form-group {
    margin-bottom: 20px;
  }
  label {
    display: block;
    margin-bottom: 8px;
    color: #666;
  }
  input[type="text"], input[type="email"], input[type="password"] {
    width: calc(100% - 20px);
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }
  input[type="submit"]:hover {
    background-color: #45a049;
  }
</style>
</head>
<body>

<div class="container">
  <h2>Registro</h2>
  <form action="" method="post">
    <div class="form-group">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" id="contrasena" name="contrasena">
    </div>
    <div class="form-group">
      <label for="secret_question">Pregunta Secreta:</label>
      <input type="text" class="form-control" id="secret_question" name="secret_question">
    </div>
    <div class="form-group">
      <label for="secret_answer">Respuesta:</label>
      <input type="text" class="form-control" id="secret_answer" name="secret_answer">
    </div>
    <center>
      <button type="submit" name="guardar" class="btn btn-dark btn-outline-light col-4">Registrar</button>
      <a href="index.php" class="btn btn-dark btn-outline-light col-4">Regresar</a>
    </center>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="@sweetalert2/theme-dark/dark.css">

<script type="text/javascript">
$(document).ready(function() {
  $("form").submit(function(event) {
    let formatonombre = /^[a-zA-Z\s]+$/;
    let formatoemail = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;

    if ($("#nombre").val() == "") {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Debe ingresar un nombre',
        showConfirmButton: false,
        timer: 3000
      });
      event.preventDefault();
      return false;
    } else if ($("#email").val() == "" || !formatoemail.test($("#email").val())) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Debe ingresar un correo electrónico válido',
        showConfirmButton: false,
        timer: 3000
      });
      event.preventDefault();
      return false;
    } else if ($("#contrasena").val() == "") {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Debe ingresar una contraseña',
        showConfirmButton: false,
        timer: 3000
      });
      event.preventDefault();
      return false;
    } else if ($("#secret_question").val() == "") {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Debe ingresar una pregunta secreta',
        showConfirmButton: false,
        timer: 3000
      });
      event.preventDefault();
      return false;
    } else if ($("#secret_answer").val() == "") {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Debe ingresar la respuesta secreta',
        showConfirmButton: false,
        timer: 3000
      });
      event.preventDefault();
      return false;
    }
  });
});
</script>

</body>
</html>
