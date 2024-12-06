<?php
session_start();
include('../config/conexion.php');

// Verificar que el usuario es un administrador
if ($_SESSION['rol'] != 'administrador') {
    header('Location: login.php');
    exit();
}

// Manejo del formulario para agregar admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') { // Agregar nuevo administrador
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rol = 'administrador';

        // Validar que el correo no esté ya registrado
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "El correo ya está registrado.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password, rol) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $rol);

            if ($stmt->execute()) {
                $success_message = "Administrador registrado correctamente.";
            } else {
                $error_message = "Error al registrar el administrador.";
            }
        }
        $stmt->close();
    }
}

// Manejo del formulario para actualizar datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Actualizar el administrador en la base de datos
    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $id);

    if ($stmt->execute()) {
        $success_message = "Administrador actualizado correctamente.";
    } else {
        $error_message = "Error al actualizar el administrador.";
    }

    $stmt->close();
}

// Manejo del formulario para eliminar el administrador
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Eliminar el administrador de la base de datos
    $sql = "DELETE FROM users WHERE id = ? AND rol = 'administrador'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success_message = "Administrador eliminado correctamente.";
    } else {
        $error_message = "Error al eliminar el administrador.";
    }

    $stmt->close();
}

// Recuperar el nombre de usuario del administrador activo
$active_username = $_SESSION['user'];

// Recuperar la lista de administradores
$sql = "SELECT id, username, email FROM users WHERE rol = 'administrador' AND username != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $active_username);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    <link rel="stylesheet" href="../Assets/css/administrarAdmin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        function confirmDelete(id) {
            if (confirm("¿Estás seguro de que deseas eliminar a este usuario?")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function editUser(id, username, email) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-email').value = email;
            $('#editModal').modal('show');
        }        
    </script>

</head>
<body>
    <div class="container text-white">
        <h2>Administrar Usuarios</h2>        

        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Agregar Administrador</button>

        <!-- Tabla de usuarios -->
        <?php if ($result->num_rows > 0): ?>
            <table id="admin-table" class="table table-bordered">
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

    <!-- Modal para agregar administrador -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #495057; color: #ffffff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Agregar Nuevo Administrador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Administrador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar administrador -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #495057; color: #ffffff;"> <!-- Estilo oscuro para el contenido del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Actualizar Administrador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" name="id" id="edit-id">
                    <input type="hidden" name="action" value="update">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" name="username" id="edit-username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="email" id="edit-email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Administrador</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>