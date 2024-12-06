document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');

    form.addEventListener('submit', function(event) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Evita el envío del formulario si los datos no son válidos
        if (!email || !password) {
            alert('Por favor, ingrese su correo electrónico y contraseña.');
            event.preventDefault(); // Evita el envío del formulario
        } else {
            // Verificar el formato del email con una expresión regular
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Por favor, ingrese un correo electrónico válido.');
                event.preventDefault(); // Evita el envío del formulario
            } else {
                // Si todo está correcto, redirigir a /Index.html
                // Se utiliza setTimeout para que la redirección ocurra después de la validación
                setTimeout(function() {
                    window.location.href = './Resources/Pages/Categories.html';
                }, 0);
            }
        }
    });
});
