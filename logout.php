<?php
require_once('includes/conexion.inc.php');
//Se inicia la sesión
session_start();
//Se establece que la cookie token se elimine
setcookie('token', '', time() - 1);
//Se destruye el token
if (isset($_SESSION['usuario'])) {
    $conexion = conectar();
    $conexion->query("UPDATE usuarios SET token = '' WHERE usuario = '{$_SESSION['usuario']}'");
    unset($conexion);
}
//Se destruye la sesión
session_destroy();
//Se redirige a index.php
header("Location: index.php");
