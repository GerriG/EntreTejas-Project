<?php
// Conexión a la base de datos
include('..\config\conexion.php');

// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = ''; // Inicializar la variable para mensajes de error
$success_message = ''; // Inicializar la variable para mensajes de éxito

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Rol por defecto es 'usuario'
    $rol = 'usuario';

    // Validar que el correo no esté ya registrado
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El correo ya está registrado
        $error_message = "El correo ya está registrado.";  // Guardar el mensaje de error
    } else {
        // Cifrar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos con el rol 'usuario'
        $sql = "INSERT INTO users (username, email, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $rol);

        // Ejecutar la inserción
        if ($stmt->execute()) {
            // Registro exitoso
            $success_message = "Usuario creado correctamente.";  // Guardar el mensaje de éxito
        } else {
            // Error al registrar el usuario
            $error_message = "Error al registrar el usuario.";  // Guardar el mensaje de error
        }
    }

    // Cerrar la declaración
    $stmt->close();
    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    <!-- Incluir las librerías de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Registro de Usuario</title>
    <style>
    /* Estilo global para todos los botones de SweetAlert */
    .swal2-confirm, .swal2-cancel {
        border-radius: 20px !important;
    }

    .swal2-popup {
    border-radius: 20px !important;
    }
    </style>
</head>

<body>
    <section class="bg-dark">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <!-- Imagen izquierda -->
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="../Assets/Fondos/Delivery-short.PNG" 
                                     alt="Formulario de registro" 
                                     class="img-fluid" 
                                     style="height:100%; border-radius: 1rem 0 0 1rem;" />
                            </div>
                            <!-- Formulario derecho -->
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <form action="signup.php" method="POST">
                                        <!-- Título -->
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <span class="h1 fw-bold mb-0">Entre Tejas</span>
                                        </div>
                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Crea tu cuenta</h5>

                                        <?php
                                        // Mostrar mensajes de error o éxito
                                        if (!empty($error_message)) {
                                            echo '<script>
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Oops...",
                                                        text: "' . htmlspecialchars($error_message) . '"
                                                    });
                                                  </script>';
                                        }
                                        if (!empty($success_message)) {
                                            echo '<script>
                                                    Swal.fire({
                                                        icon: "success",
                                                        title: "¡Éxito!",
                                                        text: "' . htmlspecialchars($success_message) . '"
                                                    });
                                                  </script>';
                                        }
                                        ?>

                                        <!-- Nombre de usuario -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="username">Nombre de usuario</label>
                                            <input type="text" id="username" name="username" 
                                                   class="form-control form-control-lg" required />
                                        </div>

                                        <!-- Correo electrónico -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="email">Correo electrónico</label>
                                            <input type="email" id="email" name="email" 
                                                   class="form-control form-control-lg" required />
                                        </div>

                                        <!-- Contraseña -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="password">Contraseña</label>
                                            <input type="password" id="password" name="password" 
                                                   class="form-control form-control-lg" required />
                                        </div>

                                        <!-- Botón -->
                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-dark btn-lg btn-block" type="submit">Registrar</button>
                                        </div>

                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">
                                            ¿Ya tienes una cuenta? 
                                            <a href="../login.php" style="color: #393f81;">Inicia sesión aquí</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>

</html>
