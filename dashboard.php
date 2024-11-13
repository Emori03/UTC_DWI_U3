<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo contenga la conexión PDO
require 'auth.php';
protectRoute('user'); // Solo usuarios con rol 'user' pueden acceder

date_default_timezone_set('America/Monterrey');

$lastLogin = isset($_SESSION['last_login']) ? date('d-m-Y H:i:s', $_SESSION['last_login']) : 'Nunca';

try {
    $sql = "SELECT * FROM productos";
    $stmt = $cnnPDO->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
}
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
        <h1 class="my-4 text-center">Bienvenido a Nuestra Tienda de Ropa</h1>
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
                            <h5 class="card-title" style="color: black;"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <p class="card-text" style="color: black;">$<?= number_format($producto['precio'], 2) ?></p>
                            <button class="btn btn-success btn-outline-dark comprar-btn" 
                                data-product-id="<?= $producto['id'] ?>" 
                                data-product-name="<?= htmlspecialchars($producto['nombre']) ?>" 
                                data-product-price="<?= $producto['precio'] ?>" 
                                data-product-image="data:image/jpeg;base64,<?= base64_encode($producto['imagen']) ?>">
                                Comprar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>        
</div>

<!-- Carrito de Compras -->
<div id="carrito">
    <div class="container mt-5">
        <h2 class="my-4 text-center">Carrito de Compras</h2>
        <div id="carrito-items" class="row">
            <!-- Aquí se agregarán los productos del carrito -->
        </div>
        <div class="mt-3">
            <h4>Total: $<span id="total">0</span></h4>
        </div>
    </div>
</div>

<script>
function agregarAlCarrito(productId, productName, productPrice, productImage) {
    const carritoItems = document.getElementById('carrito-items');
    const totalElement = document.getElementById('total');

    // Crear un nuevo elemento para el producto en el carrito
    const item = document.createElement('div');
    item.className = 'col-md-4 mb-4';
    item.setAttribute('data-product-id', productId);
    item.innerHTML = `
        <div class="card producto" style="background-color: #ffffff; color: #000000; border: 1px solid #ffffff;">
            <img src="${productImage}" class="card-img-top" alt="${productName}" style="height: 150px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title">${productName}</h5>
                <p class="card-text">$${parseFloat(productPrice).toFixed(2)}</p>
                <button class="btn btn-danger eliminar-btn">Eliminar</button>
            </div>
        </div>
    `;
    carritoItems.appendChild(item);

    // Sumar el precio del producto al total del carrito
    const total = parseFloat(totalElement.innerText) || 0;
    totalElement.innerText = (total + parseFloat(productPrice)).toFixed(2);

    // Añadir evento para eliminar producto
    item.querySelector('.eliminar-btn').addEventListener('click', () => {
        eliminarDelCarrito(productId, productPrice);
    });
}

function eliminarDelCarrito(productId, productPrice) {
    const carritoItems = document.getElementById('carrito-items');
    const totalElement = document.getElementById('total');

    const item = carritoItems.querySelector([data-product-id="${productId}"]);
    if (item) {
        carritoItems.removeChild(item);

        // Restar el precio del producto al total del carrito
        const total = parseFloat(totalElement.innerText) || 0;
        totalElement.innerText = (total - parseFloat(productPrice)).toFixed(2);
    }
}

// Agregar evento de clic a cada botón de compra
document.querySelectorAll('.comprar-btn').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name');
        const productPrice = button.getAttribute('data-product-price');
        const productImage = button.getAttribute('data-product-image');
        agregarAlCarrito(productId, productName, productPrice, productImage);
    });
});
</script>

</body>
</html>