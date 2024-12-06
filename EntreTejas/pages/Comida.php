<?php

session_start(); // Iniciar sesión



// Verificar si el usuario está logueado

if (!isset($_SESSION['user'])) {

    // Redirigir al login si no hay sesión activa

    header("Location: ../login.html");

    exit();

}
?>



<!DOCTYPE html>



<html lang="es">

<head>

   <!-- Configuración -->

   <meta charset="UTF-8">

   <meta http-equiv="X-UA-Compatible" content="IE-edge">

   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Título -->

   <title>Seleccione sus platillos</title>

   <!-- Icono -->

   <link id="favicon" rel="icon" href="../Assets/Logo/logoE.png" type="image/png">

   <!-- Librerías -->

   <link rel="stylesheet" href="../Assets/css/estilos.css">

   <link rel="stylesheet" href="../Assets/css/bootstrap.css">

   <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">

   <link rel="stylesheet" href="../Assets/css/menu.css">

</head>

<body style="background-color: rgb(0, 0, 0);" content="width=device-width, initial-scale=1.0" oncontextmenu="alert('Material Protegido');return false;" onselectstart="return false;" ondragstart="return false;">

   <script src="js/bootstrap.min.js"></script>



   <div class="logout-button">

      <form action="../config/logout.php" method="post">

         <button type="submit" class="btn btn-danger">Cerrar Sesión</button>

      </form>

   </div>



   <form method="get" action="Comida.php">

      <?php

      $menu = $_GET['comida'];



      switch ($menu) {

        case "":

            echo '<div class="section3">';

            echo '<br class="user-select-none">';

            echo '<br class="user-select-none">';

            echo '<center>';

            // Imprimir el saludo de bienvenida

            echo '<h1 style="color:#FFFFFF" id="sombra3" class="user-select-none">';

            echo '¡Bienvenido, ' . htmlspecialchars($_SESSION['user']) . '!';

            echo '</h1>';

            echo '<h3 style="color:#FFFFFF" id="sombra3" class="user-select-none">¿Qué deseas pedir hoy?</h3>';

            echo '</center>';

            echo '<br class="user-select-none">';

            echo '<br class="user-select-none">';

    

            // Abre el formulario que contiene las opciones

            echo '<form method="post" action="">';

            echo '<center>';

            echo '<table class="user-select-none">';

            echo '<tr>';

    

            // Opción 1: Platillos

            echo '<td>';

            echo '<label class="form-check-label" for="inlineRadio">';

            echo '<img src="../Assets/Menu/Plat.jpg" height="190" width="285" class="shadow border border-light rounded-3" id="a">';

            echo '<br><br>';

            echo '<center>';

            echo '<div class="form-check form-check-inline">';

            echo '<input class="form-check-input" style="background-color: rgba(255, 255, 255, 0);" type="radio" name="comida" id="inlineRadio" value="comidas" checked>';

            echo '<label class="form-check-label text-white" for="inlineRadio" style="margin-top: -20px;"><font size="5"><b id="a">Platillos</b></font></label>';

            echo '</div>';

            echo '</center>';

            echo '</label>';

            echo '</td>';

    

            // Opción 2: Bebidas

            echo '<td>';

            echo '<label class="form-check-label" for="inlineRadio2">';

            echo '<img src="../Assets/Menu/Beb.jpg" height="190" width="285" class="shadow border border-light rounded-3" id="a">';

            echo '<br><br>';

            echo '<center>';

            echo '<div class="form-check form-check-inline">';

            echo '<input class="form-check-input" style="background-color: rgba(255, 255, 255, 0);" type="radio" name="comida" id="inlineRadio2" value="bebidas">';

            echo '<label class="form-check-label text-primary" for="inlineRadio2" style="margin-top: -20px;"><font size="5"><b id="a">Bebidas</b></font></label>';

            echo '</div>';

            echo '</center>';

            echo '</label>';

            echo '</td>';

    

            // Opción 3: Postres

            echo '<td>';

            echo '<label class="form-check-label" for="inlineRadio3">';

            echo '<img src="../Assets/Menu/Post.jpg" height="190" width="285" class="shadow border border-light rounded-3" id="a">';

            echo '<br><br>';

            echo '<center>';

            echo '<div class="form-check form-check-inline">';

            echo '<input class="form-check-input" style="background-color: rgba(255, 255, 255, 0);" type="radio" name="comida" id="inlineRadio3" value="postres">';

            echo '<label class="form-check-label text-primary" for="inlineRadio3" style="margin-top: -20px;"><font size="5"><b id="a">Postres</b></font></label>';

            echo '</div>';

            echo '</center>';

            echo '</label>';

            echo '</td>';

    

            // Opción 4: Ver todos

            echo '<td>';

            echo '<label class="form-check-label" for="inlineRadio4">';

            echo '<img src="../Assets/Menu/Todos.jpg" height="190" width="285" class="shadow border border-light rounded-3" id="a">';

            echo '<br><br>';

            echo '<center>';

            echo '<div class="form-check form-check-inline">';

            echo '<input class="form-check-input" style="background-color: rgba(255, 255, 255, 0);" type="radio" name="comida" id="inlineRadio4" value="todos">';

            echo '<label class="form-check-label" for="inlineRadio4" style="margin-top: -20px;"><font size="5"><b id="a">Ver todos</b></font></label>';

            echo '</div>';

            echo '</center>';

            echo '</label>';

            echo '</td>';

    

            echo '</tr>';

            echo '</table>';

            echo '</center>';

            echo '<br class="user-select-none">';

            echo '<br class="user-select-none">';

            echo '<center>';

            echo '<button type="submit" style="background-color: rgba(255, 255, 255, 0);" class="btn btn-outline-dark rounded-pill" id="AC2">Enviar</button>';

            echo '</center>';

            echo '</form>'; // Cierra el formulario

            echo '</div>'; // Cierra la sección4

            break;

    }

    echo '</form>'; // Cierra el formulario    

function renderizarProductos($tipo, $claseBorde, $claseBoton, $titulo, $tablaBD) {

    echo '<script src="../Assets/js/selectImage.js"></script>';    

    echo "<br><center><h1 style='color:#FFFFFF' id='sombra3' class='user-select-none'>$titulo</h1></center><br>";

    echo "<form action='../pages/confirmar_pedido.php' method='POST'>";

    echo "<div class='table-responsive'><center><table class='table table-borderless'>";    

    // Función para obtener la conexión a la base de datos
function get_connect() {
    $conn = mysqli_connect("sql110.byethost8.com", "b8_37147179", "Mysthic2", "b8_37147179_entretejas");

    // Verificar la conexión
    if ($conn === false) {
        die("ERROR: No se pudo conectar. " . mysqli_connect_error());
    }

    return $conn; // Retornar la conexión
}

    $sql = "SELECT id, nombre, imagen, precio FROM $tablaBD";

    $result = get_connect()->query($sql);

    $contador = 0;

    if ($result->num_rows > 0) {

        while ($producto = $result->fetch_assoc()) {

            $nombre = htmlspecialchars($producto['nombre']);

            $imagenBase64 = base64_encode($producto['imagen']);

            $imagenSrc = "data:image/jpeg;base64," . $imagenBase64;

            $precio = number_format($producto['precio'], 2);

            $idProducto = $producto['id'];

            if ($contador % 4 == 0) {

                if ($contador > 0) {

                    echo '</tr>';

                }

                echo '<tr>';

            }

            echo "

            <td>

                <div class='text-center'>

                    <img src='$imagenSrc' height='190' width='285' class='shadow border $claseBorde rounded-3' id='a' onclick='toggleCheckbox(\"flexCheck$idProducto\")'>

                    <br><br>

                    <div class='form-check'>

                        <input class='form-check-input' type='checkbox' name='productos[$idProducto][seleccionado]' value='1' id='flexCheck$idProducto'>

                        <label class='form-check-label user-select-none' for='flexCheck$idProducto' style='color:#FFFFFF' id='sombra3'>

                            <font size='4'><b id='a'>$nombre</b></font>

                        </label>

                    </div>

                    <div>

                        <label style='color:#FFFFFF' for='cantidad$idProducto'>Cantidad:</label>

                        <input type='number' name='productos[$idProducto][cantidad]' id='cantidad$idProducto' min='1' max='10' value='1' class='form-control' style='width: 70px; display: inline;'>

                    </div>

                    <div style='color:#FFFFFF'>

                        <strong>Precio: $$precio</strong>

                        <input type='hidden' name='productos[$idProducto][nombre]' value='$nombre'>

                        <input type='hidden' name='productos[$idProducto][precio]' value='{$producto['precio']}'>

                    </div>

                </div>

            </td>";

            $contador++;

        }

        if ($contador % 4 != 0) {

            echo '</tr>';

        }

        echo '</table>';

        echo '</div>';

        echo '<br><center><button type="submit" class="btn ' . $claseBoton . ' rounded-pill" id="pedido">Realizar pedido</button></center>';

        echo "<br><center id='sombra2'><a class='btn btn-light rounded-pill' href='Comida.php' role='button' id='a'>

            <font size='4' id='a'>Regresar</font></a></center><br><br>";

        echo "</form>";

    } else {

        echo "<h2 style='color: white'>No hay productos disponibles.</h2>";

    }

    $conn->close();

    echo '</div>'; // Cierra la sección

}

switch ($menu) {

    case "comidas":

        echo "<div class='section0'>";

        renderizarProductos("comidas", "border-warning", "btn-success", "Seleccione los platillos que desea ordenar", "platillos");

        break;

    case "bebidas":

        echo "<div class='section1'>";

        renderizarProductos("bebidas", "border-primary", "btn-primary", "Seleccione las bebidas que desea ordenar", "bebidas");

        break;

    case "postres":

        echo "<div class='section2'>";

        renderizarProductos("postres", "border-success", "btn-success", "Seleccione los postres que desea ordenar", "postres");

        break;

        case "todos":
            // Consulta para combinar todos los productos
            $sqlTodos = "
    SELECT CONCAT('platillos_', id) AS id, nombre, imagen, precio FROM platillos
    UNION ALL
    SELECT CONCAT('bebidas_', id) AS id, nombre, imagen, precio FROM bebidas
    UNION ALL
    SELECT CONCAT('postres_', id) AS id, nombre, imagen, precio FROM postres
";

            include('../config/conexion.php');
            $resultTodos = get_connect()->query($sqlTodos);
        
            // Generar los datos en un formato compatible con renderizarProductos
            $productosTodos = [];
            if ($resultTodos && $resultTodos->num_rows > 0) {
                while ($producto = $resultTodos->fetch_assoc()) {
                    $productosTodos[] = $producto;
                }
            }
            get_connect()->close();
        
            // Renderizar los productos con los datos combinados
            echo '<script src="../Assets/js/selectImage.js"></script>';
            echo "<div class='section4'>";
            echo "<br><center><h1 style='color:#FFFFFF' id='sombra3' class='user-select-none'>Seleccione los productos que desea ordenar</h1></center><br>";
            echo "<form action='../pages/confirmar_pedido.php' method='POST'>";
            echo "<div class='table-responsive'><center><table class='table table-borderless'>";
        
            if (count($productosTodos) > 0) {
                $contador = 0;
        
                foreach ($productosTodos as $producto) {
                    $nombre = htmlspecialchars($producto['nombre']);
                    $imagenBase64 = base64_encode($producto['imagen']);
                    $imagenSrc = "data:image/jpeg;base64," . $imagenBase64;
                    $precio = number_format($producto['precio'], 2);
                    $idProducto = $producto['id'];
        
                    if ($contador % 4 == 0) {
                        if ($contador > 0) {
                            echo '</tr>';
                        }
                        echo '<tr>';
                    }
        
                    echo "
                    <td>
                        <div class='text-center'>
                            <img src='$imagenSrc' height='190' width='285' class='shadow border border-info rounded-3' id='a' onclick='toggleCheckbox(\"flexCheck$idProducto\")'>
                            <br><br>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='productos[$idProducto][seleccionado]' value='1' id='flexCheck$idProducto'>
                                <label class='form-check-label user-select-none' for='flexCheck$idProducto' style='color:#FFFFFF' id='sombra3'>
                                    <font size='4'><b id='a'>$nombre</b></font>
                                </label>
                            </div>
                            <div>
                                <label class='text-dark' for='cantidad$idProducto'>Cantidad:</label>
                                <input type='number' name='productos[$idProducto][cantidad]' id='cantidad$idProducto' min='1' max='10' value='1' class='form-control' style='width: 70px; display: inline;'>
                            </div>
                            <div style='color:#FFFFFF'>
                                <strong class='text-dark'>Precio: $$precio</strong>
                                <input type='hidden' name='productos[$idProducto][nombre]' value='$nombre'>
                                <input type='hidden' name='productos[$idProducto][precio]' value='{$producto['precio']}'>
                            </div>
                        </div>
                    </td>";
                    $contador++;
                }
        
                if ($contador % 4 != 0) {
                    echo '</tr>';
                }
        
                echo '</table>';
                echo '</div>';
                echo '<br><center><button type="submit" class="btn btn-info rounded-pill" id="pedido">Realizar pedido</button></center>';
                echo "<br><center id='sombra2'><a class='btn btn-light rounded-pill' href='Comida.php' role='button' id='a'>
                    <font size='4' id='a'>Regresar</font></a></center><br><br>";
                echo "</form>";
            } else {
                echo "<h2 style='color: white'>No hay productos disponibles.</h2>";
            }
            echo '</div>'; // Cierra la sección
            break;
        

    default:
        echo "";
}     

      ?>      
   </body>
</html>