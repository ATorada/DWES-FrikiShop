<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

//Si el usuario no está logueado se redirige a index.php
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require_once('includes/cabecera.inc.php'); ?>
    <div class="carrito">
        <h2>Carrito</h2>
        <ul>
            <?php
            //Se muestra el carrito del usuario y se calcula el precio total
            if(isset($_SESSION["carrito"]) && count($_SESSION["carrito"]) > 0){
                $conexion = conectar();
                $total = 0;
                foreach ($_SESSION["carrito"] as $producto => $cantidad) {
                    $resultado = $conexion->query("SELECT nombre, precio, oferta FROM productos WHERE codigo = $producto");
                    $producto = $resultado->fetch(PDO::FETCH_ASSOC);
                    $precio = round($producto['precio'] - ($producto['precio'] * $producto['oferta'] / 100),2);
                    $total += $precio * $cantidad;
                    echo '<li>' . $producto['nombre'] . ' - ' . ($cantidad == 1 ? $cantidad . " unidad" : $cantidad . " unidades") . ' - ' . $precio . '€/unidad' . '</li>';
                }
                echo '<li><strong>Total: ' . $total . '€</strong></li>';
            } else {
                //Si el carrito está vacío se muestra un mensaje
                echo '<li>No hay productos en el carrito</li>';
            }
            ?>
        </ul>
    </div>
</body>

</html>