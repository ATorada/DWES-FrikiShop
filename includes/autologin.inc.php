<?php
require_once('includes/conexion.inc.php');
//Se inicia la sesión
session_start();
//Se comprueba si el usuario está logueado por token pero no por sesión
if (isset($_COOKIE["token"]) && !isset($_SESSION["usuario"])) {
    $conexion = conectar();
    //Se comprueba si el token coincide con algún token de la base de datos
    $resultado = $conexion->query("SELECT * FROM usuarios WHERE token = '{$_COOKIE['token']}'");
    $usuario = $resultado->fetch(PDO::FETCH_ASSOC);
    //Si el token coincide se inicia la sesión
    if ($usuario) {
        $_SESSION["usuario"] = $usuario["usuario"];
        $_SESSION["rol"] = $usuario["rol"];
    }
}

//Se comprueba si existe el usuario
if (isset($_SESSION["usuario"])) {

    //Se comprueba si el carrito  del usuario tiene una expiración, sino la tiene se le asigna una
    if(!isset($_SESSION["expiracion"])){
        $_SESSION['expiracion']=time();
    }
    //Si la sesión del carrito ha expirado  se borra el carrito y se reinicia la expiración
    if (time() - $_SESSION['expiracion'] > 600) {
        if (isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = [];
        }
        $_SESSION['expiracion']=time();
    }
}