document.addEventListener('DOMContentLoaded', function () {
    const productos = document.querySelectorAll('.producto');
    const carrito = document.getElementById('carrito');
    const totalElement = document.getElementById('total');
    const calcularTotalBtn = document.getElementById('calcular-total');
    let carritoProductos = [];

    productos.forEach(producto => {
        producto.addEventListener('mouseover', function () {
            this.classList.add('shadow-lg');
        });

        producto.addEventListener('mouseout', function () {
            this.classList.remove('shadow-lg');
        });

        producto.querySelector('.comprar-btn').addEventListener('click', function () {
            const nombre = this.parentElement.querySelector('.card-title').textContent;
            const precio = parseFloat(this.parentElement.querySelector('.card-text').textContent.replace('$', ''));
            const imagen = this.parentElement.parentElement.querySelector('.card-img-top').src;

            // Crear un nuevo elemento de producto para el carrito
            const carritoItem = document.createElement('div');
            carritoItem.classList.add('col-md-3', 'mb-4');
            carritoItem.innerHTML = `
                <div class='card producto'>
                    <div class='img-container'>
                        <img src='${imagen}' class='card-img-top' alt='${nombre}'>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title'>${nombre}</h5>
                        <p class='card-text'>$${precio.toFixed(2)}</p>
                        <button class='btn btn-danger eliminar-btn'>Eliminar</button>
                    </div>
                </div>
            `;

            carrito.appendChild(carritoItem);
            carritoProductos.push({ nombre, precio });

            carritoItem.querySelector('.eliminar-btn').addEventListener('click', function () {
                carritoItem.remove();
                carritoProductos = carritoProductos.filter(item => item.nombre !== nombre);
                actualizarTotal();
            });

            alert('Producto añadido al carrito');
        });
    });

    calcularTotalBtn.addEventListener('click', actualizarTotal);

    function actualizarTotal() {
        const total = carritoProductos.reduce((acc, item) => acc + item.precio, 0);
        totalElement.textContent = total.toFixed(2);
    }
});

