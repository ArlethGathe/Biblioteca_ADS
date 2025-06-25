<?php
$conexion = new mysqli("localhost", "root", "", "usuarios", 3306);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
