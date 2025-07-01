<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    die('No estás autenticado para acceder a esta página.');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Préstamos</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="header">
        <h1>Gestión de Préstamos</h1>
    </div>

    <div class="btn-container">
        <a href="verificar_prestamo.php" class="btn">Verificar Préstamos</a>
        <a href="apartado_prestamo.php" class="btn">Apartado de Préstamo</a>
        <a href="registro_prestamo.php" class="btn">Registro de Préstamo</a>
        <a href="renovar_prestamo.php" class="btn">Renovación de Préstamo</a>
        <a href="registrar_devolucion.php" class="btn">Registro de Devolución</a>
    </div>
    
    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='home_usuario.php'">
    </div>
    
</body>
</html>
