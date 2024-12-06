<?php
session_start();
if ($_SESSION['rol'] != 'administrador') {
    header('Location: Comida.php'); // Redirigir si no es administrador
    exit();
}

// Conexión a la base de datos
include('../config/conexion.php');  // Corregido el camino de la conexión

// Establecer el número de pedidos a mostrar según la selección
$limit = 3; // Valor por defecto
if (isset($_POST['pedido_limit'])) {
    $limit = (int)$_POST['pedido_limit'];
}

// Consulta para obtener los últimos pedidos con el nombre del usuario
$sql = "SELECT p.id AS id, p.idUsuario, u.username AS Usuario, p.productos, p.total, 
        p.metodo_pago, p.ubicacion, p.fechaHoraPedido, p.fechaHoraEntrega 
        FROM pedidos p
        JOIN users u ON p.idUsuario = u.id
        ORDER BY p.id DESC
        LIMIT $limit"; // Aplicar el límite basado en la selección
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../Assets/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    
</head>
<body class="dark-mode">
    <div class="admin-container">
        <header class="dashboard-header text-center">
            <h1>Bienvenido, <span><?php echo htmlspecialchars($_SESSION['user']); ?></span> 🛠️</h1>
            <div class="d-flex justify-content-center align-items-center mb-3 pb-1">
                <img src="../Assets/Logo/logo.png" alt="Logo Entre Tejas" class="logo">
            </div>

        </header>

        <!-- Toggle estilizado para cambiar entre modo oscuro y claro -->
        <div class="text-center my-3">
            <div class="wrapper">                
                <input type="checkbox" id="hide-checkbox">
                <label for="hide-checkbox" class="toggle">
                    <span class="toggle-button">
                        <span class="crater crater-1"></span>
                        <span class="crater crater-2"></span>
                        <span class="crater crater-3"></span>
                        <span class="crater crater-4"></span>
                        <span class="crater crater-5"></span>
                        <span class="crater crater-6"></span>
                        <span class="crater crater-7"></span>
                    </span>
                    <span class="star star-1"></span>
                    <span class="star star-2"></span>
                    <span class="star star-3"></span>
                    <span class="star star-4"></span>
                    <span class="star star-5"></span>
                    <span class="star star-6"></span>
                    <span class="star star-7"></span>
                    <span class="star star-8"></span>
                </label>
            </div>
        </div>

        <section class="menu-section">
            <h2 class="text-primary"><i class="fas fa-list menu-icon"></i>Gestión de Pedidos</h2>

        <!-- Formulario para elegir la cantidad de pedidos y botón de actualización en una misma línea -->
        <div class="d-flex justify-content-between align-items-center">
            <button id="togglePedidosBtn" class="btn btn-info menu-item">
                <i class="fas fa-caret-down"></i> Ver últimos pedidos
            </button>
            <form method="POST" class="d-inline-flex align-items-center">
                <label for="pedido_limit" class="form-label me-2 mb-0">Seleccionar cantidad de pedidos:</label>
                <select name="pedido_limit" id="pedido_limit" class="form-select d-inline" style="width: auto;">
                    <option value="3" <?php echo ($limit == 3) ? 'selected' : ''; ?>>3 Últimos Pedidos</option>
                    <option value="5" <?php echo ($limit == 5) ? 'selected' : ''; ?>>5 Últimos Pedidos</option>
                    <option value="10" <?php echo ($limit == 10) ? 'selected' : ''; ?>>10 Últimos Pedidos</option>
                </select>
                <button type="submit" class="btn btn-info ms-2 mt-2"><i class="fas fa-sync-alt"></i> Actualizar</button>
            </form>
        </div>
            

            <!-- Sección de los pedidos, inicialmente oculta -->
            <div id="orderSection" class="order-section mt-3">
                <div class="order-card">
                    <h3>Últimos pedidos:</h3>
                    <?php
                    // Ejecutar la consulta
                    $result = $conn->query($sql);

                    // Verificar si la consulta devuelve resultados
                    if ($result && $result->num_rows > 0): ?>
                        <table class="table table-bordered order-table text-white" id="table-custom">
                            <thead>
                                <tr>
                                    <th>Pedido ID</th>
                                    <th>Usuario</th>
                                    <th>Productos</th>
                                    <th>Total</th>
                                    <th>Método de Pago</th>
                                    <th>Fecha de Pedido</th>
                                    <th>Fecha de Entrega</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Mostrar los resultados de la consulta
                                while ($row = $result->fetch_assoc()):
                                    $productos = json_decode($row['productos'], true);
                                    $productos_con_cantidad = [];
                                    if (is_array($productos)) {
                                        foreach ($productos as $producto) {
                                            $productos_con_cantidad[] = htmlspecialchars($producto['nombre']) . ' (' . $producto['cantidad'] . ')';
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Usuario']); ?></td>
                                        <td><?php echo implode(', ', $productos_con_cantidad); ?></td>
                                        <td>$<?php echo htmlspecialchars($row['total']); ?></td>
                                        <td><?php echo htmlspecialchars($row['metodo_pago']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fechaHoraPedido']); ?></td>
                                        <td><?php echo htmlspecialchars($row['fechaHoraEntrega']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay pedidos disponibles en este momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <div class="menu-item">
            <a href="GestionPedidos.php" ><i class="fas fa-box btn btn-primary"></i> Ver Todos los Pedidos</a>
        </div>
        
        <section class="menu-section">
            <h2 class="text-success"><i class="fas fa-user-cog menu-icon"></i>Gestión de Cuentas</h2>                  
            <div class="menu-item">
                <a href="AdministrarAdmin.php"><i class="fas fa-user-edit btn btn-success text-white"></i> Administrar Administradores</a>
            </div>            
            <div class="menu-item">
                <a href="AdministrarUser.php"><i class="fas fa-users btn btn-info text-white"></i> Administrar Usuarios</a>
            </div>
        </section>        

        <div class="logout-container text-center mt-4">
            <form method="post" action="..\config\logout.php">
                <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <script>
        // Script para manejar el clic en el botón de mostrar/ocultar los pedidos
        document.getElementById("togglePedidosBtn").addEventListener("click", function() {
            var orderSection = document.getElementById("orderSection");
            var icon = this.querySelector("i");

            // Alternar entre las clases 'open' y 'close' para animaciones al abrir/cerrar
            if (orderSection.classList.contains("open")) {
                orderSection.classList.remove("open");
                orderSection.classList.add("close");
            } else {
                orderSection.classList.remove("close");
                orderSection.classList.add("open");
            }

            // Alternar el icono de la flecha
            if (orderSection.classList.contains("open")) {
                icon.classList.remove("fa-caret-down");
                icon.classList.add("fa-caret-up");
            } else {
                icon.classList.remove("fa-caret-up");
                icon.classList.add("fa-caret-down");
            }
        });

        // Seleccionar el botón y el body
        const themeToggleBtn = document.getElementById('hide-checkbox');
        const body = document.body;

        // Evento de clic para alternar entre los modos
        themeToggleBtn.addEventListener('click', () => {
            // Alternar la clase de modo oscuro/claro
            if (body.classList.contains('dark-mode')) {
                body.classList.replace('dark-mode', 'light-mode');
                themeToggleBtn.textContent = 'Cambiar a Modo Oscuro';
                themeToggleBtn.classList.replace('btn-light', 'btn-dark');
            } else {
                body.classList.replace('light-mode', 'dark-mode');
                themeToggleBtn.textContent = 'Cambiar a Modo Claro';
                themeToggleBtn.classList.replace('btn-dark', 'btn-light');
            }
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>