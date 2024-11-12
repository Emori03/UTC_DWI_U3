<?php
$productos = [
    ['nombre' => 'Camiseta', 'precio' => 200, 'imagen' => 'img/camisa.jpeg'],
    ['nombre' => 'Jeans', 'precio' => 400, 'imagen' => 'img/jeans.jpeg'],
    ['nombre' => 'Chaqueta', 'precio' => 2000, 'imagen' => 'img/chaqueta.jpeg'],
    ['nombre' => 'Tacones', 'precio' => 600, 'imagen' => 'img/tacones.jpg'],
    ['nombre' => 'Falda', 'precio' => 450, 'imagen' => 'img/falda.jpg'],
    ['nombre' => 'Short', 'precio' => 300, 'imagen' => 'img/short.jpg'],
    ['nombre' => 'Tenis', 'precio' => 2500, 'imagen' => 'img/tenis.jpeg'],
    ['nombre' => 'Blusa', 'precio' => 150, 'imagen' => 'img/blusa.jpeg'],
];

foreach ($productos as $producto) {
    echo "
    <div class='col-md-3 mb-4'>
        <div class='card producto'>
            <div class='img-container'>
                <img src='{$producto['imagen']}' class='card-img-top' alt='{$producto['nombre']}'>
            </div>
            <div class='card-body'>
                <h5 class='card-title'>{$producto['nombre']}</h5>
                <p class='card-text'>\${$producto['precio']}</p>
                <button class='btn btn-primary comprar-btn'>Comprar</button>
            </div>
        </div>
    </div>";
}
?>
