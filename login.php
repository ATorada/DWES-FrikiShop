<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
}

//Si se ha enviado el formulario se comprueba que el usuario y la contraseña sean correctos
if (isset($_POST['usuario']) && isset($_POST['password'])) {

    $conexion = conectar();
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? or email = ?");
    $consulta->execute([$_POST['usuario'], $_POST['usuario']]);
    //Si existe algún usuario con ese nombre o email se comprueba la contraseña
    if ($consulta->rowCount() > 0) {
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        if (password_verify($_POST['password'], $usuario['contrasenya'])) {
            //Si la contraseña es correcta se inicia la sesión, se crea un token en caso de que se quiera recordar al usuario por 7 días y se redirige a index.php
            if (isset($_POST['recordar'])) {
                $token = bin2hex(random_bytes(90));
                $conexion->query("UPDATE usuarios SET token = '$token' WHERE usuario = '{$_POST['usuario']}'");
                setcookie('token', $token, time() + 60 * 60 * 24 * 7);
            }
            $_SESSION['usuario'] = $usuario['usuario'];
            $_SESSION['rol'] = $usuario['rol'];
            header("Location: index.php");
        } else {
            $errores[] = "<p class='error'>Usuario o email incorrecto</p>";
        }
        unset($usuario);
    } else {
        $errores[] = "<p class='error'>Usuario o email incorrecto</p>";
    }
    unset($consulta);
    unset($conexion);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia sesión</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    require_once('includes/cabecera.inc.php');
    //En caso de venir a esta página desde el formulario de registro se muestra un mensaje
    echo '<div class="iniciar">';
    echo '<h2>Inicia sesión</h2>';
    if (isset($_GET['registrado'])) {
        echo "<p class='correcto'>Usuario registrado correctamente</p>";
    }
    //Se muestran los errores
    if (isset($errores)) {
        foreach ($errores as $error) {
            echo $error;
        }
    }
    ?>
    <form action="login.php" method="post">
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password">
        <label for="recordar">Recordar sesión por 7 días</label>
        <input type="checkbox" name="recordar" id="recordar">
        <input type="submit" value="Iniciar sesión">
    </form>
    </div>
</body>

</html>