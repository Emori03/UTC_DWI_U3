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

// Función para establecer mensajes de sesión
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Función para obtener y eliminar mensajes de sesión
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $flash_message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash_message;
    }
    return null;
}

// Agregar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'agregar') {
    if (isset($_POST['nombre']) && isset($_POST['precio']) && isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $imagen = $_FILES['imagen'];

        // Verificar si el archivo es una imagen
        $imagen_tipo = mime_content_type($imagen['tmp_name']);
        if (strpos($imagen_tipo, 'image/') === false) {
            setFlashMessage('danger', 'El archivo seleccionado no es una imagen.');
        } else {
            // Leer la imagen en binario
            $imagen_datos = file_get_contents($imagen['tmp_name']);

            try {
                $sql = "INSERT INTO productos (nombre, precio, imagen) VALUES (:nombre, :precio, :imagen)";
                $stmt = $cnnPDO->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':precio', $precio);
                $stmt->bindParam(':imagen', $imagen_datos, PDO::PARAM_LOB);
                $stmt->execute();

                setFlashMessage('success', '¡Producto agregado exitosamente!');
            } catch (PDOException $e) {
                setFlashMessage('danger', 'Error: ' . $e->getMessage());
            }
        }
    } else {
        setFlashMessage('danger', 'Por favor, complete todos los campos y asegúrese de que el archivo se haya cargado correctamente.');
    }

    // Redirigir para evitar reenvío del formulario
    header('Location: admin_dashboard.php');
    exit;
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);

    try {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $cnnPDO->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        setFlashMessage('success', '¡Producto eliminado exitosamente!');
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error: ' . $e->getMessage());
    }

    // Redirigir para evitar reenvío del formulario
    header('Location: admin_dashboard.php');
    exit;
}

// Mostrar productos
try {
    $sql = "SELECT * FROM productos";
    $stmt = $cnnPDO->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
}
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
        .alert {
            transition: opacity 0.5s ease-out;
        }
        .form-container {
            display: none; /* Ocultar el formulario por defecto */
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
            margin-top: 20px;
        }

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
    </style>
</head>
<body>

<p style="color: #ffffff">Último inicio de sesión: <span id="last-login"><?php echo $lastLogin; ?></span></p>


<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
    
</div>


<div class="container mt-5">
        <!-- Botón para mostrar el formulario -->
        <button id="toggleFormBtn"  class="btn btn-outline-light">Agregar Producto</button>
        
        <!-- Contenedor del formulario -->
        <div id="formContainer" class="form-container">
            <h2>Agregar Producto</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="agregar">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>

        <!-- Mostrar alertas -->
        <?php
        $flash_message = getFlashMessage();
        if ($flash_message):
        ?>
            <div class="alert alert-<?= htmlspecialchars($flash_message['type']) ?>" role="alert">
                <?= htmlspecialchars($flash_message['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar productos -->
         <center>
         <h2 class="mt-5" style="color: #ffffff;">Productos</h2>
        </center>
        <br><br>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="producto">
                        <div class="img-container">
                            <?php if ($producto['imagen']): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            <?php else: ?>
                                <img src="images/placeholder.png" alt="Imagen no disponible">
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="color: #ffffff;"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <p class="card-text"style="color: #ffffff;" >$<?= number_format($producto['precio'], 2) ?></p>
                            <a href="?eliminar=<?= $producto['id'] ?>" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar u ocultar el formulario al presionar el botón
            document.getElementById('toggleFormBtn').addEventListener('click', function() {
                var formContainer = document.getElementById('formContainer');
                if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                    formContainer.style.display = 'block';
                } else {
                    formContainer.style.display = 'none';
                }
            });

            // Ocultar alertas automáticamente después de 3 segundos
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500); // Elimina el elemento después de que la animación se completa
                }, 3000); // 3000 ms = 3 segundos
            });
        });
    </script>
</body>
</html>
