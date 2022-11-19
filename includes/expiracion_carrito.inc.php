<?php
//Se comprueba si existe el usuario
if (isset($_SESSION["usuario"])) {

    //Se comprueba si el carrito  del usuario tiene una expiración, sino la tiene se le asigna una
    if (!isset($_SESSION["expiracion"])) {
        $_SESSION['expiracion'] = time();
    }
    //Si la sesión del carrito ha expirado  se borra el carrito y se reinicia la expiración
    if (time() - $_SESSION['expiracion'] > 600) {
        if (isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = [];
        }
        $_SESSION['expiracion'] = time();
    }
}
