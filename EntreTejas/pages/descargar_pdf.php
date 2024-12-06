<?php

// Configurar zona horaria
date_default_timezone_set('America/Mexico_City');

// Suponiendo que el ID del pedido está disponible en la URL
$idPedido = $_GET['idPedido'];

// Función para obtener la conexión a la base de datos
function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión
}

// Consultar los detalles del pedido y el nombre de usuario
$query = "
    SELECT p.id, p.productos, p.total, p.metodo_pago, p.ubicacion, p.fechaHoraPedido, p.fechaHoraEntrega, u.username
    FROM pedidos p
    JOIN users u ON p.idUsuario = u.id
    WHERE p.id = ?
";

$conn = get_connect();
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idPedido);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el pedido
if ($result->num_rows > 0) {
    $pedido = $result->fetch_assoc();
    $productos = json_decode($pedido['productos'], true);
    $total = $pedido['total'];
    $metodo_pago = $pedido['metodo_pago'];
    $ubicacion = $pedido['ubicacion'];
    $fechaHoraPedido = $pedido['fechaHoraPedido'];
    $fechaHoraEntrega = $pedido['fechaHoraEntrega'];
    $username = $pedido['username'];
} else {
    echo "Pedido no encontrado.";
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
$pdf->Cell(200, 10, 'Factura de Pedido #' . $idPedido, 0, 1, 'C');

// Línea horizontal separadora
$pdf->SetDrawColor(0, 51, 102);
$pdf->Line(10, 20, 200, 20);

// Información del usuario
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 12);
$pdf->SetTextColor(0, 0, 0); // Color negro para texto general
$pdf->Cell(100, 10, 'Usuario: ' . $username, 0, 1);
$pdf->Cell(100, 10, 'Fecha y hora de pedido: ' . $fechaHoraPedido, 0, 1);
$pdf->Cell(100, 10, 'Fecha y hora estimada de entrega: ' . $fechaHoraEntrega, 0, 1);
$pdf->Cell(100, 10, 'Metodo de pago: ' . $metodo_pago, 0, 1);
$pdf->Cell(100, 10, 'Ubicacion de entrega: ' . $ubicacion, 0, 1);

// Detalles de los productos
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Productos:', 0, 1);
$pdf->SetFont('Arial', '', 10);

// Crear una tabla para los productos
$pdf->SetFillColor(220, 220, 220); // Color de fondo para las celdas de la tabla

// Cabecera de la tabla
$pdf->Cell(45, 10, 'Producto', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Precio', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Subtotal', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Foto', 1, 1, 'C', true); // Esto ya está bien, pero debe ir en la misma fila

// Consultar las imágenes de los productos en las tablas `platillos`, `bebidas`, `postres`
foreach ($productos as $producto) {
    $nombre = $producto['nombre'];
    $cantidad = $producto['cantidad'];
    $precio = $producto['precio'];
    $subtotal = $producto['subtotal'];

    // Consultar en las tres tablas (platillos, bebidas, postres)
    $query_imagen = "
        SELECT imagen FROM platillos WHERE nombre = ? 
        UNION 
        SELECT imagen FROM bebidas WHERE nombre = ? 
        UNION 
        SELECT imagen FROM postres WHERE nombre = ? 
    ";

    $stmt_imagen = $conn->prepare($query_imagen);
    $stmt_imagen->bind_param("sss", $nombre, $nombre, $nombre);
    $stmt_imagen->execute();
    $result_imagen = $stmt_imagen->get_result();

    // Verificar si hay una imagen asociada al producto
    if ($result_imagen->num_rows > 0) {
        $imagen = $result_imagen->fetch_assoc()['imagen'];

        // Convertir la imagen en base64
        $image = imagecreatefromstring($imagen);
        if ($image) {
            // Crear un recurso de imagen en formato JPEG
            ob_start();
            imagejpeg($image);
            $imageDataJPEG = ob_get_contents();
            ob_end_clean();

            // Convertir la imagen JPEG a base64
            $imageBase64 = base64_encode($imageDataJPEG);

            // Agregar los datos del producto a la tabla
            $pdf->Cell(45, 30, $nombre, 1, 0, 'C');
            $pdf->Cell(30, 30, $cantidad, 1, 0, 'C');
            $pdf->Cell(30, 30, '$' . number_format($precio, 2), 1, 0, 'C');
            $pdf->Cell(40, 30, '$' . number_format($subtotal, 2), 1, 0, 'C');

            // Dibujar manualmente el borde de la celda de la foto
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $ancho = 35;
            $alto = 25;
            $pdf->Rect($x, $y, 40, 30); // Dibuja el borde de la celda donde va la imagen

            // Insertar la imagen (centrada en la celda)
            $posX = $x + (40 - $ancho) / 2;
            $posY = $y + (30 - $alto) / 2;
            $pdf->Image('data:image/jpeg;base64,' . $imageBase64, $posX, $posY, $ancho, $alto, 'JPEG');

            $pdf->Ln(30); // Salto de línea después de cada fila de datos
        } else {
            // Si no se puede crear la imagen, solo mostrar texto
            $pdf->Cell(45, 30, $nombre, 1, 0, 'C');
            $pdf->Cell(30, 30, $cantidad, 1, 0, 'C');
            $pdf->Cell(30, 30, '$' . number_format($precio, 2), 1, 0, 'C');
            $pdf->Cell(40, 30, '$' . number_format($subtotal, 2), 1, 0, 'C');
            $pdf->Cell(40, 30, '', 1, 1, 'C');
        }
    } else {
        $pdf->Cell(45, 30, $nombre, 1, 0, 'C');
        $pdf->Cell(30, 30, $cantidad, 1, 0, 'C');
        $pdf->Cell(30, 30, '$' . number_format($precio, 2), 1, 0, 'C');
        $pdf->Cell(40, 30, '$' . number_format($subtotal, 2), 1, 0, 'C');
        $pdf->Cell(40, 30, '', 1, 1, 'C');
    }
}

// Resumen del pedido
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Total: $' . number_format($total, 2), 0, 1);

// Cerrar la conexión
$conn->close();

// Salida del PDF (forzar descarga)
$pdf->Output('D', 'factura_pedido_' . $idPedido . '.pdf');
?>