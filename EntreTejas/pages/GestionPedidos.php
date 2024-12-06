<?php
session_start();
if ($_SESSION['rol'] != 'administrador') {
    header('Location: Comida.php'); // Redirigir si no es administrador
    exit();
}

// Conexión a la base de datos
include('../config/conexion.php');  // Corregido el camino de la conexión

// Consulta para obtener los pedidos con el nombre del usuario
$sql = "SELECT p.idUsuario, u.username AS Usuario, p.productos, p.total, 
        p.metodo_pago, p.ubicacion, p.fechaHoraPedido, p.fechaHoraEntrega 
        FROM pedidos p
        JOIN users u ON p.idUsuario = u.id
        ORDER BY p.fechaHoraPedido DESC"; // Ordenar por Fecha y Hora del Pedido, más nuevos primero

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    <link rel="stylesheet" href="../Assets/css/gestionarPedido.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">     
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>    

</head>
<body>
    <div class="admin-container">
        <h1>Ver Pedidos</h1>
        <?php if ($result->num_rows > 0): ?>
        <table id="pedidosTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Ubicación</th>
                    <th>Fecha y Hora del Pedido</th>
                    <th>Fecha y Hora de Entrega</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Usuario']); ?></td>
                    <td>
                        <?php
                        $productos = json_decode($row['productos'], true);
                        if (is_array($productos)) {
                            $productos_con_cantidad = [];
                            foreach ($productos as $producto) {
                                $productos_con_cantidad[] = htmlspecialchars($producto['nombre']) . ' (' . $producto['cantidad'] . ')';
                            }
                            echo implode(', ', $productos_con_cantidad);
                        } else {
                            echo "Error al leer productos";
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['total']); ?></td>
                    <td><?php echo htmlspecialchars($row['metodo_pago']); ?></td>
                    <td><?php echo htmlspecialchars($row['ubicacion']); ?></td>
                    <td><?php echo htmlspecialchars($row['fechaHoraPedido']); ?></td>
                    <td><?php echo htmlspecialchars($row['fechaHoraEntrega']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No hay pedidos disponibles en este momento.</p>
        <?php endif; ?>

        <a href="../pages/Dashboard.php" class="btn btn-secondary">Regresar</a>
    </div>

    <script>
        $(document).ready(function () {
            $('#pedidosTable').DataTable({
                order: [[5, 'desc']], // Ordenar por Fecha y Hora del Pedido (columna 5)
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" // Traducción al español
                }
            });
        });
    </script>
</body>
</html>

