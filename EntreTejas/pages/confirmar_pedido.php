<?php
session_start();

//Configurar zona horaria
date_default_timezone_set('America/Mexico_City');

// Verificar si hay productos seleccionados
if (!isset($_POST['productos'])) {
    echo "No hay productos seleccionados.";
    exit();
}

// Capturar los productos del formulario
$productos = $_POST['productos'];

// Guardar el método de pago y la ubicación de entrega si se ha enviado el formulario
$metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : '';
$ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : '';

// Incluir la conexión a la base de datos
require_once '../config/conexion.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pedido</title>
    <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css"> <!-- Asegúrate de que la ruta sea correcta -->
    <link rel="stylesheet" href="../Assets/css/confirmar.css">
</head>
<body class="section1"> <!-- Usa "section1" como fondo -->

    <div class="container mt-5">
        <h2>Confirmación de Pedido</h2>

        <!-- Mostrar los detalles del pedido -->
        <div class="order-card">
  <div class="order-container">
    <div class="left">
      <div class="order-status-ind"></div>
    </div>
    <div class="order-right">
      <div class="order-text-wrap">
        <h3>Resumen de tu pedido:</h3>
        <ul>
          <?php
          $productosSeleccionados = []; // Array para almacenar los productos seleccionados
          $total = 0; // Inicializar el total
          
          foreach ($productos as $id => $producto) {
              if (isset($producto['seleccionado']) && $producto['seleccionado']) {
                  // Comprobar si las claves existen antes de usarlas
                  $nombre = htmlspecialchars($producto['nombre']);
                  $cantidad = (int)$producto['cantidad']; // Convertir a entero
                  $precio = (float)$producto['precio']; // Convertir a float
          
                  // Verificar que la cantidad y el precio sean válidos
                  if ($cantidad > 0 && $precio >= 0) {
                      $subtotal = $cantidad * $precio;
                      $total += $subtotal; // Acumular el total
          
                      // Almacenar el producto en el array
                      $productosSeleccionados[] = [
                          'id' => $id,
                          'nombre' => $nombre,
                          'cantidad' => $cantidad,
                          'precio' => $precio,
                          'subtotal' => $subtotal
                      ];
          
                      // Imprimir los productos seleccionados (opcional)
                      echo "<li>$nombre - Cantidad: $cantidad - Precio: $" . number_format($subtotal, 2) . "</li>";
                  }
              }
          }
          
          // Ahora puedes pasar el array $productosSeleccionados a procesarPedido
          ?>
          <!-- Enviar los productos seleccionados como JSON -->
          
                  </ul>
                  <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                </div>      
              </div>
            </div>
          </div>


        <!-- Opciones de método de pago -->
        <form action="../config/ProcesarPedido.php" method="POST">
    <div class="payment-method">
        <h3 class="lite">Método de Pago</h3>                
        <div class="radio-inputs">
            <label>
                <input class="radio-input" type="radio" name="metodo_pago" value="tarjeta" <?php echo (isset($metodo_pago) && $metodo_pago == 'tarjeta') ? 'checked' : ''; ?>>
                <span class="radio-tile">
                    <span class="radio-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H4Zm0 6h16v6H4v-6Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M5 14a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1Zm5 0a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2h-5a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                    </svg>
                    </span>
                    <span class="radio-label">Tarjeta</span>
                </span>
            </label>
            <label>
                <input class="radio-input" type="radio" name="metodo_pago" value="efectivo" <?php echo (isset($metodo_pago) && $metodo_pago == 'efectivo') ? 'checked' : ''; ?>>
                <span class="radio-tile">
                    <span class="radio-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M7 6a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-2v-4a3 3 0 0 0-3-3H7V6Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M2 11a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7Zm7.5 1a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z" clip-rule="evenodd"/>
                        <path d="M10.5 14.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                    </svg>
                    </span>
                    <span class="radio-label">Efectivo</span>
                </span>
            </label>   
        </div>
    </div>

    <div class="delivery-location">
        <h3>Dirección de Entrega</h3>               
        <input type="text" autocomplete="off" name="ubicacion" class="input" placeholder="Ingrese su dirección" value="<?php echo isset($ubicacion) ? htmlspecialchars($ubicacion) : ''; ?>" required>
    </div>

    <input type="hidden" name="productos" value="<?php echo htmlspecialchars(json_encode($productos)); ?>"> <!-- Enviar los productos como JSON -->
    <button type="submit" class="btn btn-primary">Proceder</button>
</form>
    </div>
</body>
</html>


