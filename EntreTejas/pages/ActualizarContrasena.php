<?php

session_start();

include('../config/conexion.php');

$conn = get_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];

    $nueva_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Asegúrate de usar hashing

    // Actualizar la contraseña
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nueva_password, $username);
    $stmt->execute();

    // Incluir el encabezado HTML y Bootstrap
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <<link id="favicon" rel="icon" href="./Assets/Logo/logoE.png" type="image/png">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        </style>
        <title>Actualizar Contraseña</title>
    </head>
    <body>';

    if ($stmt->affected_rows > 0) {
        // Mostrar SweetAlert de éxito
        echo '<script>
            Swal.fire({
                title: "¡Éxito!",
                text: "La contraseña ha sido restablecida con éxito.",
                icon: "success",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../login.php"; // Redirigir a la página de login
                }
            });
        </script>';
    } else {
        // Mostrar SweetAlert de error
        echo '<script>
            Swal.fire({
                title: "Error",
                text: "Error al restablecer la contraseña. Por favor, intente de nuevo.",
                icon: "error",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../pages/ResetPass.html"; // Volver al formulario de reset
                }
            });
        </script>';
    }

    $stmt->close();
    $conn->close();

    // Cerrar la etiqueta <body> y </html>
    echo '</body></html>';
}

?>