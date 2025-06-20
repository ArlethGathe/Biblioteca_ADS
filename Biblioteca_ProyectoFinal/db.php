<?php
$conexion = new mysqli("localhost", "root", "", "biblioteca", 3307);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
