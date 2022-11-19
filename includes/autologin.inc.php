<?php
require_once('includes/conexion.inc.php');
//Se inicia la sesión
session_start();
//Se comprueba si el usuario está logueado por token pero no por sesión
if (isset($_COOKIE["token"]) && !isset($_SESSION["usuario"])) {
    $conexion = conectar();
    //Se comprueba si el token coincide con algún token de la base de datos
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE token = ?");
    $consulta->execute([$_COOKIE["token"]]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    //Si el token coincide se inicia la sesión
    if ($usuario) {
        $_SESSION["usuario"] = $usuario["usuario"];
        $_SESSION["rol"] = $usuario["rol"];
    }
    unset($usuario);
    unset($consulta);
    unset($conexion);
}
