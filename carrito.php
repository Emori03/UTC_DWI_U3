<?php
session_start();
require 'conexion.php'; 
require 'auth.php';
protectRoute('user'); // Solo usuarios con rol 'user' pueden acceder

// Verificar si el carrito está vacío
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<div class='alert alert-warning'>Tu carrito está vacío.</div>";
    exit;
}

// Eliminar producto del carrito en la sesión si se recibe el ID
if (isset($_GET['eliminar'])) {
    $idProducto = $_GET['eliminar'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $idProducto) {
            unset($_SESSION['cart'][$key]); // Eliminar el producto del carrito
            break;
        }
    }

    // Reindexar el carrito para que los productos no se revuelvan
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: carrito.php"); // Redirigir después de eliminar
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/styles1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #dddddd;
            margin-bottom: 20px;
        }

        .card-img-top {
            height: 150px;
            object-fit: contain; /* Asegura que la imagen se vea completa */
            width: 100%;
        }

        .producto {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Centrar el texto en los recuadros de nombre y precio */
        .card-body {
            text-align: center; /* Centrar el contenido */
        }

        .card-title {
            font-size: 1.2em;
            font-weight: bold;
        }

        .card-text {
            font-size: 1.1em;
            margin: 10px 0;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 12px 0; /* Botón más largo */
            width: 100%; /* Botón ocupa todo el ancho */
            font-size: 1.1em;
            border-radius: 5px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .container {
            margin-top: 30px;
        }

        .row {
            display: flex;
            justify-content: space-between; /* Para mantener los productos distribuidos */
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="container mt-5">
<h1 class="my-4 text-center" style="font-family: 'Haettenschweiler', sans-serif;">
Carrito de Compras
</h1>
   
    <div class="row" id="carrito-items">
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $item):
            $total += $item['price'];
        ?>
            <div class="col-md-4 mb-4">
                <div class="card producto" style="background-color: #ffffff;" data-product-id="<?= $item['id'] ?>">
                    <img src="<?= $item['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="card-text">$<?= number_format($item['price'], 2) ?></p>
                        <a href="carrito.php?eliminar=<?= $item['id'] ?>" class="btn btn-danger w-100">Eliminar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-right mt-4">
        <h4>Total: $<span id="total"><?= number_format($total, 2) ?></span></h4>
    </div>
    <a href="dashboard.php" class="btn btn-dark btn-outline-light mx-1">Regresar</a>
</div>

<script>
// Actualiza el total de forma dinámica si se eliminaran productos del carrito
document.addEventListener('DOMContentLoaded', () => {
    const totalElement = document.getElementById('total');
    let total = parseFloat(totalElement.innerText) || 0;
    
    // Opcionalmente puedes hacer alguna lógica adicional si es necesario
});
</script>

</body>
</html>
