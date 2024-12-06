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

// Consultar todos los administradores sin incluir la contraseña
$query = "
    SELECT id, username, email
    FROM users
    WHERE rol = 'administrador'
";

$conn = get_connect();
$result = $conn->query($query);

// Verificar si hay administradores
if ($result->num_rows === 0) {
    echo "No hay administradores disponibles.";
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
$pdf->Cell(200, 10, 'Reporte de Administradores', 0, 1, 'C');

// Línea horizontal separadora
$pdf->SetDrawColor(0, 51, 102);
$pdf->Line(10, 20, 200, 20);

// Espaciado inicial
$pdf->Ln(10);

// Imprimir los encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0); // Color negro para los encabezados
$pdf->Cell(30, 10, 'ID', 1, 0, 'C');        // ID de Administrador
$pdf->Cell(50, 10, 'Nombre de Usuario', 1, 0, 'C'); // Nombre de Usuario
$pdf->Cell(60, 10, 'Email', 1, 1, 'C');      // Email

// Imprimir las filas de la tabla
$pdf->SetFont('Arial', '', 12);
while ($admin = $result->fetch_assoc()) {
    $idAdmin = $admin['id'];
    $username = $admin['username'];
    $email = $admin['email'];    

    // Imprimir una fila con los datos del administrador
    $pdf->Cell(30, 10, $idAdmin, 1, 0, 'C');
    $pdf->Cell(50, 10, $username, 1, 0, 'C');
    $pdf->Cell(60, 10, $email, 1, 1, 'C');
}

// Cerrar la conexión
$conn->close();

// Obtener la fecha actual
$fechaActual = date('Y-m-d');

// Salida del PDF (forzar descarga) con nombre dinámico
$pdf->Output('D', 'Reporte_de_administradores_' . $fechaActual . '.pdf');

?>