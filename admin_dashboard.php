<?php
session_start();
require 'conexion.php';
require 'auth.php';
protectRoute('admin'); // Verificación del rol de administrador

// Verificar si el usuario está autenticado y si el session_key es válido
if (!isAuthenticated() || !isValidSession()) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('America/Monterrey');
$lastLogin = isset($_SESSION['last_login']) ? date('d-m-Y H:i:s', $_SESSION['last_login']) : 'Nunca';

$query = "
    SELECT u.id AS user_id, u.nombre AS user_name, p.nombre AS product_name
    FROM usuarios u
    LEFT JOIN carrito c ON u.id = c.usuario_id
    LEFT JOIN productos p ON c.producto_id = p.id
    ORDER BY u.id, p.nombre
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function updateLastLogin() {
            fetch('get_last_login.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('last-login').textContent = data;
                });
        }

        document.addEventListener('DOMContentLoaded', updateLastLogin);
    </script>
</head>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de que este archivo exista y contenga los estilos adicionales -->
    <style>
        /* Agrega aquí tus estilos adicionales */
        .carousel-item img {
            width: 100%;
            height: auto;
        }
        .body-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>



<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
            <a class="navbar-brand" href="#">Bienvenido: <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>

                <li class="nav-item">
                    <a class="nav-link" href="#productos">PRODUCTOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#carrito">CARRITO DE COMPRAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">SALIR</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="collapse navbar-collapse justify-content-left">
    <p>Último inicio de sesión: <span id="last-login"><?php echo $lastLogin; ?></span></p></div>
</div>

<table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Productos en el Carrito</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $currentUser = '';
                foreach ($rows as $row) {
                    if ($currentUser !== $row['user_name']) {
                        if ($currentUser !== '') {
                            echo '</ul></td></tr>';
                        }
                        echo '<tr><td>' . htmlspecialchars($row['user_name']) . '</td><td><ul>';
                        $currentUser = $row['user_name'];
                    }
                    if ($row['product_name']) {
                        echo '<li>' . htmlspecialchars($row['product_name']) . '</li>';
                    }
                }
                if ($currentUser !== '') {
                    echo '</ul></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>