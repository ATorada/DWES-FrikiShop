<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
}

//En caso de que se reciban datos se comprueba su validez
if (count($_POST) > 0) {
    $conexion = conectar();
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? or email = ?");
    $consulta->execute([$_POST["usuario"], $_POST["email"]]);
    if ($consulta->rowCount() > 0) {
        $errores[] = "<p class='error'>El usuario o el email ya está en uso</p>";
    } else {
        if (!preg_match("/^[A-z]{1}[A-z0-9._]{2,61}@[A-z0-9]{1,251}.[A-z]{2,4}$/", $_POST['email'])) {
            $errores[] = "<p class='error'>El email no es válido.</p>";
        }
        if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $_POST['usuario'])) {
            $errores[] = "<p class='error'>El usuario no es válido, debe tener entre 3 y 20 letras y números.</p>";
        }
        if (!preg_match("/^[a-zA-Z0-9]{8,255}$/", $_POST['password'])) {
            $errores[] = "<p class='error'>La contraseña no es válida, debe tener un mínimo de 8 letras y números.</p>";
        }
        //En caso de que no se haya encontrado ningún error se intenta insertar el usuario en la base de datos
        if (!isset($errores)) {
            //Se comprueba que las contraseñas coincidan
            if ($_POST['password'] == $_POST['password2']) {
                //Se cifran la contraseña
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                try {
                    //Se inserta el usuario en la base de datos
                    $consulta = $conexion->prepare("INSERT INTO usuarios (usuario, email, contrasenya, token) VALUES (?, ? ,?, '')");
                    $consulta->execute([$_POST['usuario'], $_POST['email'], $_POST['password']]);
                    unset($consulta);
                    unset($conexion);
                    header("Location: login.php?registrado=true");
                } catch (PDOException $e) {
                    $errores[] = "<p class='error'>Error al registrar el usuario</p>";
                }
            } else {
                $errores[] = "<p class='error'>Las contraseñas no coinciden</p>";
            }
        }
        unset($consulta);
        unset($conexion);
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrate</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require_once('includes/cabecera.inc.php'); ?>
    <div class="errores_registro">
        <?php
        if (isset($errores)) {
            foreach ($errores as $e) {
                echo $e;
            }
        }
        ?>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>

</html>