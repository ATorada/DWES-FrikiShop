<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

//Si el usuario está loguado entonces se le envía a la página de index
if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once('includes/cabecera.inc.php'); ?>
    <h1>Ofertas</h1>
    <div class="ofertas">
        <?php
        //Se obtienen los productos de la base de datos que tengan oferta
        $conexion = conectar();
        $resultado = $conexion->query("SELECT * FROM productos WHERE oferta > 0");
        while ($producto = $resultado->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="producto">';
            echo '<h3>' . $producto['nombre'] . '</h3>';
            echo '<img src="img/' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
            echo '<p>' . $producto['categoria'] . '</p>';
            echo '<p><strong>' . $producto['precio'] . '€</strong></p>';
            echo '<p><strong>' . $producto['oferta'] . '% de descuento</strong></p>';
            echo '<p><strong>' . round($producto['precio'] - ($producto['precio'] * $producto['oferta'] / 100),2) . '€</strong></p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>