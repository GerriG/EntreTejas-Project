<?php
session_start(); // Iniciar la sesión
include('..\config\conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para obtener el usuario por su email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El usuario existe
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Guardar sesión
            $_SESSION['idUsuario'] = $user['id'];
            $_SESSION['user'] = $user['username']; 
            $_SESSION['rol'] = $user['rol'];

            // Redirigir según el rol
            if ($user['rol'] == 'administrador') { 
                header('Location: ../pages/Dashboard.php');
            } else {
                header('Location: ../pages/Comida.php');
            }
            exit(); 
        } else {
            $_SESSION['error_message'] = "Contraseña incorrecta";
        }
    } else {
        $_SESSION['error_message'] = "Usuario no encontrado";
    }

    $stmt->close();
    header('Location: ../login.php'); // Redirige al formulario de login
    exit();
}

$conn->close();
?>