<?php

session_start(); // Iniciar la sesión

// Función para obtener la conexión a la base de datos
function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión.
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Llamar a la función para obtener la conexión
    $conn = get_connect();

    // Consulta para obtener el usuario por su email
    $sql = "SELECT * FROM users WHERE email = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Vincular el parámetro
    $stmt->execute(); // Ejecutar la consulta
    $result = $stmt->get_result(); // Obtener el resultado

    // Verificar si el usuario existe
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
            exit(); // Asegurar que no se ejecute más código

        } else {
            $_SESSION['error_message'] = "Contraseña incorrecta";
        }

    } else {
        $_SESSION['error_message'] = "Usuario no encontrado";
    }

    // Cerrar el statement y la conexión
    $stmt->close();
    $conn->close();

    // Mantener los datos ingresados para reutilizarlos
    $_SESSION['login_email'] = $email;

    // Redirigir al formulario de login
    header('Location: ../login.php');
    exit();
}

?>
