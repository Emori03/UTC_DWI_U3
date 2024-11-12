<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo contenga la conexión PDO
require 'auth.php';
protectRoute('user'); // Solo usuarios con rol 'user' pueden acceder

date_default_timezone_set('America/Monterrey');
$lastLogin = isset($_SESSION['last_login']) ? date('d-m-Y H:i:s', $_SESSION['last_login']) : 'Nunca';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carousel y Tienda de Ropa</title>
    <link rel="stylesheet" href="css/styles1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Bienvenid@: <?php echo $_SESSION['nombre']; ?></a>
                </li>
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



   


    <!-- Carousel -->
    <div class="Body-container">
        <div class="carousel">
            <div class="carrusel-list" id="carrusel-list">
                <button class="carrusel-arrow carrusel-prev" id="button-prev" data-button="button-prev">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left"
                        class="svg-inline--fa fa-chevron-left fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 320 512">
                        <path fill="currentColor"
                            d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z">
                        </path>
                    </svg>
                </button>
                <div class="carrusel-track" id="track">
                    <?php
                    for ($i = 1; $i <= 3; $i++) {
                        echo '
                        <div class="carrusel">
                            <div>
                                <a href="/">
                                    <h4><small>Imagen</small> ' . $i . '</h4>
                                    <picture>
                                        <img src="images/' . $i . '.jpg" alt="imagen ' . $i . '">
                                    </picture>
                                </a>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
                <button class="carrusel-arrow carrusel-next" id="button-next" data-button="button-next">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right"
                        class="svg-inline--fa fa-chevron-right fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 320 512">
                        <path fill="currentColor"
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                    </path>
                </svg>
                </button>
            </div>
        </div>
    </div>

    <br>

    <!-- Tienda de Ropa -->
     <div id="productos">
    <div class="container">
        <h1 class="my-4 justify-content-center" >Bienvenido a Nuestra Tienda de Ropa</h1>
        <div id="productos" class="row">
            <?php include 'php/productos.php'; ?>
        </div>
    </div>
    </div>



    <!-- Carrito de Compras -->
    <div id="carrito">
    <div class="container mt-5">
        <h2>Carrito de Compras</h2>
        <div id="carrito" class="row">
            <!-- Aquí se agregarán los productos del carrito -->
        </div>
        <div class="mt-3">
            <h4>Total: $<span id="total">0</span></h4>
            <button class="btn btn-success" id="calcular-total">Calcular Total</button>
        </div>
    </div>
                </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/scripts1.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
