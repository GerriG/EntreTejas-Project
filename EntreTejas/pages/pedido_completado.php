<?php
// Asegúrate de que tienes la variable $idPedido disponible
session_start();

// Verifica que el usuario esté logueado, y si no redirige al login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.html");
    exit();
}

// Obtén el idPedido de alguna manera (de la base de datos, sesión, etc.)
$idPedido = isset($_GET['idPedido']) ? (int)$_GET['idPedido'] : 0;

// Calcula la fecha y hora actuales
date_default_timezone_set('America/Mexico_City');
$fechaPedido = new DateTime();
$fechaEntrega = clone $fechaPedido;
$fechaEntrega->modify('+45 minutes');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Completado</title>
    <!-- Librerías -->
    <link rel="stylesheet" href="../Assets/css/estilos.css">
    <link rel="stylesheet" href="../Assets/css/bootstrap.css">
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/css/pedido.css">
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="delivery"> <!-- Clase para el fondo -->

    <div class="container text-center section1" style="max-width: 65%;">
        <h2 class="mt-4 mb-4">Pedido Completado</h2>
        <img src="../Assets/Pedido/check.webp" class="icon" alt="Check Image">
        
        <div class="order-details mb-4">
            <p><strong>Fecha y Hora del Pedido:</strong> <?php echo $fechaPedido->format('d-m-Y h:i:s A'); ?></p>
            <p><strong>Fecha Estimada de Entrega:</strong> <?php echo $fechaEntrega->format('d-m-Y h:i:s A'); ?></p>
        </div>

        <div class="confirmation-message">
            <p>¡Gracias por tu pedido! Se está preparando y estará listo en 45 minutos.</p>
        </div>
    </div>

    <div class="text-center" style="margin-top:-25px; position: relative; z-index: 100; display: flex; justify-content: center; width: 100%;">
        <a class="btn btn-primary rounded-pill" href="Comida.php" role="button" style="font-size: 1.5rem; padding: 10px 15px;">
            Volver al menú
        </a>
        <form action="../config/logout.php" method="post" style="margin-left: 300px;">
            <button type="submit" class="btn btn-danger rounded-pill" style="font-size: 1.5rem; padding: 10px 15px;">Cerrar Sesión</button>
        </form>
    </div>

    <script>
        window.onload = function() {
            // Forzar la descarga del PDF después de cargar la página
            setTimeout(function() {
                window.location.href = 'descargar_pdf.php?idPedido=<?php echo $idPedido; ?>';
            }, 500); // 500 ms para asegurar que la página se ha cargado
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



