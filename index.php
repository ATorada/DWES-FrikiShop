<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

//Si se recibe una acción y un producto se añade o elimina del carrito
if (isset($_GET['accion']) && isset($_GET['id'])) {

    switch ($_GET['accion']) {
        //Si la acción es añadir se añade el producto al carrito
        case 'add':
            if (isset($_SESSION['carrito'][$_GET['id']])) {
                echo "El producto ya está en el carrito";
                $_SESSION['carrito'][$_GET['id']]++;
            } else {
                $_SESSION['carrito'][$_GET['id']] = 1;
            }
            break;
        //Si la acción es eliminar se elimina el producto del carrito
        case 'remove':
            if (isset($_SESSION['carrito'][$_GET['id']]) && $_SESSION['carrito'][$_GET['id']] >= 1) {
                $_SESSION['carrito'][$_GET['id']]--;
            }
            if ($_SESSION['carrito'][$_GET['id']] == 0) {
                unset($_SESSION['carrito'][$_GET['id']]);
            }
            break;
        //Si la acción es vaciar se vacía el carrito
        case 'delete':
            if (isset($_SESSION['carrito'][$_GET['id']])) {
                unset($_SESSION['carrito'][$_GET['id']]);
            }
            break;
    }

    header('Location: index.php');
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrikiShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require_once('includes/cabecera.inc.php'); ?>

    <?php
    //Si hay un usuario logueado se muestran los productos
    if (isset($_SESSION['usuario'])) {
        echo '<div class="productos">';
        $conexion = conectar();
        $resultado = $conexion->query("SELECT * FROM productos");
        while ($producto = $resultado->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='producto'>";
            echo "<div><img src='img/{$producto['imagen']}' alt='{$producto['nombre']}' class='imagen_producto'></div>";
            echo "<h3>{$producto['nombre']}</h3>";
            echo "<div>{$producto['precio']} €</div>";
            echo "<div>{$producto['categoria']}</div>";
            echo "<div>Stock: {$producto['stock']}</div>";
            if($producto['oferta'] > 0){
                echo "<div class='oferta'>EN OFERTA</div>";
            }
            echo "<div class='botones'>";
            echo "<a href='index.php?accion=add&id=" . $producto['codigo'] . "'><img src='img/mas.png'></a>";
            echo "<a href='index.php?accion=remove&id=" . $producto['codigo'] . "'><img src='img/menos.png'></a>";
            echo "<a href='index.php?accion=delete&id=" . $producto['codigo'] . "'><img src='img/papelera.png'></a>";
            echo "</div>";
            echo "</div>";
        }
        echo '</div>';
    } else {
        //Si no hay un usuario logueado se muestra un formulario de registro
        ?>
        <div class="registrar">
            <h2>Regístrate</h2>
            <form action="registro.php" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" required>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
                <label for="password">Repite la Contraseña</label>
                <input type="password" name="password2" id="password2" required>
                <input type="submit" value="Registrarse">
            </form>
            <a href="login.php" class="login_boton">Iniciar sesión</a>
        </div>
        <a href="ofertas.php" class="ofertas_img"><img src="img/ofertas.png" alt="enlace_ofertas"></a>
    <?php
    }
    ?>
</body>

</html>