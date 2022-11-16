<?php
function conectar()
{
    //Se prepara la conexión
    $dsn = 'mysql:host=localhost;dbname=tiendamercha';
    $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    try {
        //Se intenta conectar
        $conexion = new PDO($dsn, 'Lumos', 'Nox', $opciones);
    } catch (PDOException $e) {
        $conexion = null;
    }
    //Se devuelve la conexión
    return $conexion;
}