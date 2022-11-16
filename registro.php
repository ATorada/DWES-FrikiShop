<?php
// *** Imports ***

//Se intenta iniciar la sesión
require_once('includes/autologin.inc.php');

require_once('includes/conexion.inc.php');

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
    <title>Regístrate</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require_once('includes/cabecera.inc.php'); ?>
    <div class="errores_registro">
        <?php
        //En caso de que se reciban datos se comprueba su validez
        if (count($_POST) > 0) {
            $conexion = conectar();
            $resultado = $conexion->query("SELECT * FROM usuarios WHERE usuario = '{$_POST['usuario']}' or email = '{$_POST['email']}'");
            if ($resultado->rowCount() > 0) {
                echo "<p class='error'>El usuario o el email ya está en uso</p>";
            } else {
                if (!preg_match("/^[A-z]{1}[A-z0-9._]{2,61}@[A-z0-9]{1,251}.[A-z]{2,4}$/", $_POST['email'])) {
                    echo "<p class='error'>El email no es válido.</p>";
                    $errorEncontrado = true;
                }
                if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $_POST['usuario'])) {
                    echo "<p class='error'>El usuario no es válido, debe tener entre 3 y 20 letras y números.</p>";
                    $errorEncontrado = true;
                }
                if (!preg_match("/^[a-zA-Z0-9]{8,255}$/", $_POST['password'])) {
                    echo "<p class='error'>La contraseña no es válida, debe tener un mínimo de 8 letras y números.</p>";
                    $errorEncontrado = true;
                }
                //En caso de que no se haya encontrado ningún error se intenta insertar el usuario en la base de datos
                if (!isset($errorEncontrado)) {
                    //Se comprueba que las contraseñas coincidan
                    if ($_POST['password'] == $_POST['password2']) {
                        //Se cifran la contraseña
                        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        try {
                            //Se inserta el usuario en la base de datos
                            $conexion->query("INSERT INTO usuarios (usuario, email, contrasenya, token) VALUES ('{$_POST['usuario']}', '{$_POST['email']}' ,'{$_POST['password']}', '')");
                            header("Location: login.php?registrado=true");
                        } catch (PDOException $e) {
                            echo $e;
                            echo "<p class='error'>Error al registrar el usuario</p>";
                        }
                    } else {
                        echo "<p class='error'>Las contraseñas no coinciden</p>";
                    }
                }
            }
        }
        ?>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>

</html>