<?php

// Configurar zona horaria
date_default_timezone_set('America/Mexico_City');

// Función para obtener la conexión a la base de datos
function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión
}

// Consultar todos los pedidos junto con el nombre del usuario
$query = "
    SELECT p.id, p.productos, p.total, p.metodo_pago, p.ubicacion, p.fechaHoraPedido, p.fechaHoraEntrega, u.username
    FROM pedidos p
    JOIN users u ON p.idUsuario = u.id
";

$conn = get_connect();
$result = $conn->query($query);

// Verificar si hay pedidos
if ($result->num_rows === 0) {
    echo "No hay pedidos disponibles.";
    exit();
}

require('../fpdf/fpdf.php');

// Instanciar FPDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Agregar un logo (opcional)
$pdf->Image('../Assets/Logo/logo.png', 10, 10, 30); // Ajusta la posición y tamaño del logo

// Títulos y estilo
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 51, 102); // Color azul para el título
$pdf->Cell(200, 10, 'Reporte de Pedidos', 0, 1, 'C');

// Línea horizontal separadora
$pdf->SetDrawColor(0, 51, 102);
$pdf->Line(10, 20, 200, 20);

// Espaciado inicial
$pdf->Ln(10);

// Iterar sobre los pedidos
while ($pedido = $result->fetch_assoc()) {
    $productos = json_decode($pedido['productos'], true);
    $total = $pedido['total'];
    $metodo_pago = $pedido['metodo_pago'];
    $ubicacion = $pedido['ubicacion'];
    $fechaHoraPedido = $pedido['fechaHoraPedido'];
    $fechaHoraEntrega = $pedido['fechaHoraEntrega'];
    $username = $pedido['username'];
    $idPedido = $pedido['id'];

    // Información del pedido
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetTextColor(0, 0, 0); // Color negro para texto general
    $pdf->Cell(100, 10, 'Pedido ID: ' . $idPedido, 0, 1);
    $pdf->Cell(100, 10, 'Usuario: ' . $username, 0, 1);
    $pdf->Cell(100, 10, 'Fecha y hora de pedido: ' . $fechaHoraPedido, 0, 1);
    $pdf->Cell(100, 10, 'Fecha y hora estimada de entrega: ' . $fechaHoraEntrega, 0, 1);
    $pdf->Cell(100, 10, 'Metodo de pago: ' . $metodo_pago, 0, 1);
    $pdf->Cell(100, 10, 'Ubicacion de entrega: ' . $ubicacion, 0, 1);

    // Detalles de los productos
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Productos:', 0, 1);
    $pdf->SetFont('Arial', '', 10);

    // Crear una tabla para los productos
    $pdf->SetFillColor(220, 220, 220); // Color de fondo para las celdas de la tabla

    // Cabecera de la tabla
    $pdf->Cell(45, 10, 'Producto', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Precio', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C', true);

    foreach ($productos as $producto) {
        $nombre = $producto['nombre'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];
        $subtotal = $producto['subtotal'];

        $pdf->Cell(45, 10, $nombre, 1, 0, 'C');
        $pdf->Cell(30, 10, $cantidad, 1, 0, 'C');
        $pdf->Cell(30, 10, '$' . number_format($precio, 2), 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');
    }

    // Resumen del pedido
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Total: $' . number_format($total, 2), 0, 1);

    // Separador entre pedidos
    $pdf->Ln(10);
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);
}

// Cerrar la conexión
$conn->close();

// Obtener la fecha actual
$fechaActual = date('Y-m-d');

// Salida del PDF (forzar descarga) con nombre dinámico
$pdf->Output('D', 'Reporte_de_pedidos_' . $fechaActual . '.pdf');

?>
