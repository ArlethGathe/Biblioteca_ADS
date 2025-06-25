<?php
session_start();
include("./all/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND clave='$clave'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 1) {
        $_SESSION["usuario"] = $usuario;
        header("Location: home.php");
    } else {
        $error = "Credenciales inválidas";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="all/styles.css">
</head>
<body class="Sinicio">
    <div class="iniS">
    <h2>Iniciar Sesión</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <input type="submit" value="Entrar" >
        <input type="button" value="¿No tienes cuenta? Regístrate" onclick="window.location.href='all/register.php'">
        
        <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    </form>
</div>
</body>
</html>
