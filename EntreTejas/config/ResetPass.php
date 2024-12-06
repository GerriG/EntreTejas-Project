<?php

session_start();

function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión
}

$conn = get_connect();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];

    $email = $_POST['email'];

    

    // Verificar si el nombre de usuario y el correo electrónico existen

    $sql = "SELECT id FROM users WHERE username = ? AND email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $username, $email);

    $stmt->execute();

    $stmt->store_result();

    

    // Incluir el encabezado HTML y Bootstrap

    echo '<!DOCTYPE html>

    <html lang="es">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <link id="favicon" rel="icon" href="./Assets/Logo/logoE.png" type="image/png">

        <style>

            body {

                background-color: #343a40; /* Fondo oscuro */

                color: #ffffff; /* Texto blanco */

            }

            .container {

                max-width: 500px;

                margin-top: 100px;

                padding: 20px;

                background-color: #495057; /* Fondo más claro para el contenedor */

                border-radius: 8px;

                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);

            }

            h2 {

                margin-bottom: 20px;

                color: #ffffff; /* Títulos blancos */

            }

            .text-danger {

                color: #dc3545; /* Color de texto de error */

            }

            .btn-primary {

                background-color: #007bff; /* Color primario */

                border-color: #007bff; /* Color del borde */

            }

            .btn-primary:hover {

                background-color: #0056b3; /* Color al pasar el mouse */

                border-color: #0056b3; /* Color del borde al pasar el mouse */

            }

        </style>

        <title>Restablecer Contraseña</title>

    </head>

    <body>';



    if ($stmt->num_rows > 0) {

        // Si coinciden, mostrar el formulario para restablecer la contraseña

        echo '<div class="container">';

        echo '<h2>Restablecer Contraseña</h2>';

        echo '<form action="ActualizarContrasena.php" method="POST">';

        echo '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">';

        echo '<div class="form-group">';

        echo '<label for="password">Nueva Contraseña:</label>';

        echo '<input type="password" class="form-control" name="password" required>';

        echo '</div>';

        echo '<button type="submit" class="btn btn-primary">Restablecer Contraseña</button>';

        echo '</form>';

        echo '</div>';

    } else {

        echo '<div class="container">';

        echo '<h2>Error</h2>';

        echo '<p class="text-danger">El nombre de usuario o el correo electrónico son incorrectos.</p>';

        echo '<a href="..\pages\ResetPass.html" class="btn btn-secondary">Volver</a>';

        echo '</div>';

    }

    

    $stmt->close();

    $conn->close();



    // Cerrar la etiqueta <body> y </html>

    echo '</body></html>';

}

?>