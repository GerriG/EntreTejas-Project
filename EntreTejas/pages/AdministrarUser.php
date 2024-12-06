<?php
session_start();

// Función para obtener la conexión a la base de datos
function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión
}

// Obtener la conexión una sola vez
$conn = get_connect();

// Verificar que el usuario es un administrador
if ($_SESSION['rol'] != 'administrador') {
    header('Location: login.php');
    exit();
}

// Manejo del formulario para crear un nuevo usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'register') {
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
        $error_message = "El correo ingresado, ya existe.";
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
            $success_message = "Usuario creado correctamente.";
        } else {
            // Error al registrar el usuario
            $error_message = "Error al crear el usuario.";
        }
    }

    // Cerrar la declaración
    $stmt->close();
}

// Manejo del formulario para actualizar datos de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    // Actualizar el usuario en la base de datos
    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ? AND rol = 'usuario'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $id);

    if ($stmt->execute()) {
        $success_message = "Usuario actualizado correctamente.";
    } else {
        $error_message = "Error al actualizar el usuario.";
    }

    $stmt->close();
}

// Manejo del formulario para eliminar el usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM users WHERE id = ? AND rol = 'usuario'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success_message = "Usuario eliminado correctamente.";
    } else {
        $error_message = "Error al eliminar el usuario.";
    }

    $stmt->close();
}

// Recuperar la lista de usuarios
$sql = "SELECT id, username, email FROM users WHERE rol = 'usuario'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../Assets/css/administrarAdmin.css">
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-white">Administrar Usuarios</h2>   

        <!-- Contenedor para alinear los botones -->
    <div class="d-flex justify-content-between mb-3">
        <!-- Botón para imprimir reporte -->
        <a href="ReporteUsuarios.php" class="btn btn-primary">Imprimir Reporte</a>

        <!-- Botón para agregar administrador -->
        <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarUsuario">Agregar Usuario</button>
    </div>          
        
        <?php if ($result->num_rows > 0): ?>
            <table id="users-table" class="table table-bordered menu-item">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Correo Electrónico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <button class="btn btn-warning" onclick="editUser(<?php echo $row['id']; ?>, '<?php echo $row['username']; ?>', '<?php echo $row['email']; ?>')">Actualizar</button>
                                <form id="delete-form-<?php echo $row['id']; ?>" method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">No hay usuarios registrados.</div>
        <?php endif; ?>
        <a href="../pages/Dashboard.php" class="btn btn-secondary">Regresar</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#admin-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                }
            });
        });

        function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

        function editUser(id, username, email) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-email').value = email;
            $('#editModal').modal('show');
        }

        // Mostrar mensajes de éxito o error con SweetAlert
        <?php if (isset($success_message)): ?>
            Swal.fire({
                title: 'Éxito',
                text: "<?php echo $success_message; ?>",
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        <?php elseif (isset($error_message)): ?>
            Swal.fire({
                title: 'Error',
                text: "<?php echo $error_message; ?>",
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>

    <!-- Modal para agregar usuario -->
    <div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarUsuarioLabel">Agregar Nuevo Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Nombre de Usuario</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <input type="hidden" name="action" value="register">
                            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    <!-- Modal para editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Header de la modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Cuerpo de la modal -->
            <div class="modal-body">
                <form method="POST" action="">
                    <!-- Campo oculto para el ID -->
                    <input type="hidden" name="id" id="edit-id" required>
                    <!-- Nombre de usuario -->
                    <div class="form-group mb-3">
                        <label for="edit-username">Nombre de Usuario</label>
                        <input type="text" name="username" id="edit-username" class="form-control" required>
                    </div>
                    <!-- Correo electrónico -->
                    <div class="form-group mb-3">
                        <label for="edit-email">Correo Electrónico</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>
                    <!-- Acción del formulario -->
                    <input type="hidden" name="action" value="update">
                    <!-- Botón de guardar -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <script>
        function editUser(id, username, email) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-username').value = username;
    document.getElementById('edit-email').value = email;
    $('#modalEditarUsuario').modal('show');
}


        $(document).ready(function() {
            $('#users-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                }
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>