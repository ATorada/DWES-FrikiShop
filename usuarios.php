<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

require_once('includes/expiracion_carrito.inc.php');

//Comprueba de que se haya iniciado sesión y que el usuario sea administrador
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "admin") {
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require_once('includes/cabecera.inc.php'); ?>
    <h1>Usuarios</h1>
    <div class="usuarios">
        <?php
        //Si se ha iniciado sesión y el usuario es administrador se muestran los usuarios
        $conexion = conectar();
        $resultado = $conexion->query("SELECT * FROM usuarios");
        while ($usuario = $resultado->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="usuario">';
            echo '<h3>' . $usuario['usuario'] . '</h3>';
            echo '<p>' . $usuario['email'] . '</p>';
            echo '<p>' . $usuario['rol'] . '</p>';
            echo '</div>';
        }
        unset($resultado);
        unset($conexion);
        ?>
    </div>
</body>

</html>