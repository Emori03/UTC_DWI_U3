document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const contrasena = document.getElementById('contrasena').value;

    fetch('api.php?endpoint=login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            contrasena: contrasena
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Redirigir según el rol
            window.location.href = data.role === 'admin' ? 'admin_dashboard.php' : 'dashboard.php';
        } else {
            alert(data.message); // Mostrar mensaje de error
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
