<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Gestión de Usuarios</h2>
    <form method="post" action="">
        <div style="max-width: 400px; margin: auto;">
            <a href="agregar_usuario.php"><input type="button" value="Añadir Usuario"></a>
            <a href="editar_usuario.php"><input type="button" value="Editar Usuario"></a>
            <a href="eliminar_usuario.php"><input type="button" value="Eliminar Usuario"></a>
            <a href="listar_usuarios.php"><input type="button" value="Ver Todos"></a>
        </div>
    </form>
    <div style="text-align: center; margin-top: 20px;">
    <a href="home.php">
        <input type="button" value="Volver a Inicio">
    </a>
</div>

</body>
</html>
