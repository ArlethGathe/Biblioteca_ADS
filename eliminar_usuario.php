<?php
include("db.php");

// Obtener usuarios disponibles
$usuarios = $conexion->query("SELECT id, usuario FROM usuarios");

// Si se envía el formulario
if (isset($_POST["eliminar"])) {
    $id = $_POST["id_usuario"];
    $sql = "DELETE FROM usuarios WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Usuario eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar: " . $conexion->error;
    }

    // Recargar la lista actualizada
    $usuarios = $conexion->query("SELECT id, usuario FROM usuarios");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Eliminar Usuario</h2>

    <form method="post">
        <label for="id_usuario">Selecciona un usuario para eliminar:</label>
        <select name="id_usuario" required>
            <option value="">-- Elige uno --</option>
            <?php while ($fila = $usuarios->fetch_assoc()) { ?>
                <option value="<?php echo $fila["id"]; ?>">
                    <?php echo $fila["usuario"]; ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" name="eliminar" value="Eliminar Usuario" style="background-color:#cc0000;">
    </form>

    <?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="gestionar_usuarios.php">← Volver a gestión</a>
    </div>
</body>
</html>
