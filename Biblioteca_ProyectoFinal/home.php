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
    <title>Biblioteca Principal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION["usuario"]; ?> 👋</h2>
    <form action="logout.php" method="post">
        <button type="submit">Cerrar sesión</button>
    </form>
<a href="gestionar_usuarios.php">
    Gestionar Usuarios
</a>


</body>
</html>
