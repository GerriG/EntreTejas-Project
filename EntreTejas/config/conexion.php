<?php
$servername = "localhost";
$username = "root";  // Cambiar si el usuario es distinto
$password = "";      // Cambiar si tienes contraseña
$dbname = "entretejas";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
