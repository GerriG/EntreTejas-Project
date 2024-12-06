<?php
session_start();
include('../config/conexion.php');

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
        echo '<div class="container mt-5">';
        echo '<h2>Éxito</h2>';
        echo '<p>La contraseña ha sido restablecida con éxito.</p>';
        echo '<a href="..\login.html" class="btn btn-light">Iniciar Sesión</a>'; // Botón claro en modo oscuro
        echo '</div>';
    } else {
        echo '<div class="container mt-5">';
        echo '<h2>Error</h2>';
        echo '<p class="text-danger">Error al restablecer la contraseña. Por favor, intente de nuevo.</p>';
        echo '<a href=".\pages\ResetPass.html" class="btn btn-secondary">Volver</a>';
        echo '</div>';
    }
    
    $stmt->close();
    $conn->close();

    // Cerrar la etiqueta <body> y </html>
    echo '</body></html>';
}
?>

