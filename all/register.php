<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    $sql = "INSERT INTO usuarios (usuario, correo, clave) VALUES ('$usuario', '$correo', '$clave')";
    if ($conexion->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        $error = "Error al registrar";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <input type="submit" value="Registrarse">
        <a href="../index.php">Volver al login</a>
        <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    </form>
</body>
</html>
