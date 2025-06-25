<?php
include("/all/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $rol = $_POST["rol"];

    $sql = "INSERT INTO usuarios (usuario, correo, clave, rol) VALUES ('$usuario', '$correo', '$clave', '$rol')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Usuario agregado exitosamente";
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Agregar Usuario</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="Administrador">Administrador</option>
            <option value="Lector">Lector</option>
            <option value="Bibliotecario">Bibliotecario</option>
        </select>

        <input type="submit" value="Agregar Usuario">
    </form>

    <div style="text-align: center; margin-top: 15px;">
        <a href="gestionar_usuarios.php">← Volver a gestión</a>
    </div>

    <?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>
</body>
</html>
