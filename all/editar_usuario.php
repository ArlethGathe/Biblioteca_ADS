<?php
include("db.php");

// Obtener usuarios para el desplegable
$usuarios = $conexion->query("SELECT id, usuario FROM usuarios");

// Cuando se selecciona un usuario para editar
if (isset($_POST["seleccionar"])) {
    $id = $_POST["id_usuario"];
    $datos = $conexion->query("SELECT * FROM usuarios WHERE id = $id")->fetch_assoc();
}

// Cuando se guardan los cambios
if (isset($_POST["guardar"])) {
    $id = $_POST["id_usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $rol = $_POST["rol"];

    $sql = "UPDATE usuarios SET correo='$correo', clave='$clave', rol='$rol' WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Usuario actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Editar Usuario</h2>

    <form method="post">
        <label for="id_usuario">Selecciona un usuario:</label>
        <select name="id_usuario" required>
            <option value="">-- Elige uno --</option>
            <?php while ($fila = $usuarios->fetch_assoc()) { ?>
                <option value="<?php echo $fila["id"]; ?>"
                    <?php if (isset($datos) && $datos["id"] == $fila["id"]) echo "selected"; ?>>
                    <?php echo $fila["usuario"]; ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" name="seleccionar" value="Editar">
    </form>

    <?php if (isset($datos)) { ?>
        <form method="post">
            <input type="hidden" name="id_usuario" value="<?php echo $datos["id"]; ?>">

            <input type="email" name="correo" value="<?php echo $datos["correo"]; ?>" required>
            <input type="text" name="clave" value="<?php echo $datos["clave"]; ?>" required>

            <label for="rol">Rol:</label>
            <select name="rol">
                <option value="Administrador" <?php if ($datos["rol"] == "Administrador") echo "selected"; ?>>Administrador</option>
                <option value="Lector" <?php if ($datos["rol"] == "Lector") echo "selected"; ?>>Lector</option>
                <option value="Bibliotecario" <?php if ($datos["rol"] == "Bibliotecario") echo "selected"; ?>>Bibliotecario</option>
            </select>

            <input type="submit" name="guardar" value="Guardar Cambios">
        </form>
    <?php } ?>

    <?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>

    <div style="text-align:center; margin-top: 20px;">
        <a href="gestionar_usuarios.php">← Volver a gestión</a>
    </div>
</body>
</html>
