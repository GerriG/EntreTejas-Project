<?php
session_start();

// Función para establecer la conexión a la base de datos
function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión
}

$conn = get_connect();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Verificar si el nombre de usuario y el correo electrónico existen en la base de datos
    $sql = "SELECT id FROM users WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    // Incluir el encabezado HTML, Bootstrap y SweetAlert
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link id="favicon" rel="icon" href="./Assets/Logo/logoE.png" type="image/png">
        <style>
            body {
                background-color: #343a40;
                color: #ffffff;
            }
            .container {
                max-width: 500px;
                margin-top: 100px;
                padding: 20px;
                background-color: #495057;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            }
            h2 {
                margin-bottom: 20px;
                color: #ffffff;
            }
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }
            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
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

        // Agregar SweetAlert para advertir al usuario sobre guardar la contraseña
        echo '<script>
                Swal.fire({
                    title: "¡Restablecer Contraseña!",
                    text: "Recuerda guardar tu contraseña en un lugar seguro.",
                    icon: "warning",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    if (result.isConfirmed) {
                    // El alert simplemente se cierra sin redirigir a otra página
                    Swal.close();
                    }
                });
              </script>';
    } else {
        // Si no coinciden, mostrar un SweetAlert de error
        echo '<script>
                Swal.fire({
                    title: "Error",
                    text: "El nombre de usuario o el correo electrónico son incorrectos.",
                    icon: "error",
                    confirmButtonText: "Volver"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../pages/ResetPass.php";
                    }
                });
              </script>';
    }

    // Cerrar la conexión y la declaración
    $stmt->close();
    $conn->close();

    // Cerrar la etiqueta <body> y </html>
    echo '</body></html>';
} else {
    // Si el formulario no se ha enviado, mostrar el formulario de verificación de usuario
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Restablecer Contraseña</title>
        <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body {
                background-color: #343a40;
                color: #ffffff;
            }
            .container {
                max-width: 500px;
                margin-top: 100px;
                padding: 20px;
                background-color: #495057;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            }
            h2 {
                margin-bottom: 20px;
                color: #ffffff;
            }
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }
            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h2>Restablecer Contraseña</h2>
            <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">
                <div class="form-group">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Ingrese su correo electrónico:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Verificar y Restablecer Contraseña</button>
            </form>
        </div>
    </body>
    </html>';
}
?>