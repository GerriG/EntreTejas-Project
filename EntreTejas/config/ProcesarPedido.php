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

// Configurar zona horaria
date_default_timezone_set('America/Mexico_City');

// Verificar si el usuario está logueado
if (!isset($_SESSION['user'])) {
    // Redirigir al login si no hay sesión activa
    header("Location: ../login.html");
    exit();
}

// Obtener el ID del usuario de la sesión
$idUsuario = $_SESSION['idUsuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los productos enviados en el formulario (como JSON)
    $productos = isset($_POST['productos']) ? json_decode($_POST['productos'], true) : [];
    $fechaHoraPedido = date("Y-m-d H:i:s");

    // Calcular la fechaHoraEntrega sumando 45 minutos a fechaHoraPedido
    $fechaHoraEntrega = date("Y-m-d H:i:s", strtotime($fechaHoraPedido . " + 45 minutes"));

    // Obtener el método de pago y la dirección de entrega
    $metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : '';
    $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : '';

    // Validar que los productos no estén vacíos
    if (empty($productos)) {
        echo "Datos incompletos.";
        exit();
    }

    // Inicializar variables para el total y los productos seleccionados
    $total = 0;
    $productosSeleccionados = [];

    // Recorrer los productos para calcular el total y almacenar los productos seleccionados
    foreach ($productos as $producto) {
        if (isset($producto['seleccionado']) && $producto['seleccionado']) {
            $cantidad = isset($producto['cantidad']) ? (int)$producto['cantidad'] : 0;
            $precio = isset($producto['precio']) ? (float)$producto['precio'] : 0;

            if ($cantidad > 0 && $precio >= 0) {
                // Calcular el subtotal de este producto
                $subtotal = $cantidad * $precio;
                $total += $subtotal; // Sumar al total

                // Almacenar el producto seleccionado en el array
                $productosSeleccionados[] = [
                    'nombre' => htmlspecialchars($producto['nombre']), // Sanitización
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ];
            }
        }
    }

    // Verificar que haya productos seleccionados
    if (empty($productosSeleccionados)) {
        echo "No se seleccionaron productos.";
        exit();
    }

    // Convertir los productos seleccionados a formato JSON para almacenarlos en la base de datos
    $productos_json = json_encode($productosSeleccionados);

    // Insertar el pedido en la base de datos
    $queryPedido = "INSERT INTO pedidos (idUsuario, productos, total, metodo_pago, ubicacion, fechaHoraPedido, fechaHoraEntrega) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

    $conn = get_connect();
    $stmtPedido = $conn->prepare($queryPedido);

    if ($stmtPedido) {
        $stmtPedido->bind_param("isdssss", $idUsuario, $productos_json, $total, $metodo_pago, $ubicacion, $fechaHoraPedido, $fechaHoraEntrega);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($stmtPedido->execute()) {
            // Obtener el ID del pedido recién insertado
            $idPedido = $stmtPedido->insert_id;

            // Redirigir a la página de pedido completado pasando el ID del pedido
            header("Location: ../pages/pedido_completado.php?idPedido=" . $idPedido);
            exit();
        } else {
            echo "Error al procesar el pedido: " . $stmtPedido->error;
        }

        // Cerrar el statement
        $stmtPedido->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }

    // Cerrar la conexión
    $conn->close();

} else {
    echo "Acceso no autorizado.";
}
?>