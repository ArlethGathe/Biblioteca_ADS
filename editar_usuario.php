<?php

include("db.php");

// Obtener usuarios para el desplegable
$usuarios = $pdo->query("SELECT id_usuarios, usuario FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);

// Cuando se selecciona un usuario para editar
$datos = null;
if (isset($_POST["seleccionar"])) {
    $id = $_POST["id_usuarios"];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
    $stmt->execute([$id]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Cuando se guardan los cambios
if (isset($_POST["guardar"])) {
    $id = $_POST["id_usuarios"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $fechaNac = $_POST["fechaNac"];
    $rol = $_POST["rol"];

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET correo = ?, clave = ?, fecha_nacimiento = ?, rol = ? WHERE id_usuarios = ?");
        $stmt->execute([$correo, $clave, $fechaNac, $rol, $id]);
        $mensaje = "Usuario actualizado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
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
    <label for="id_usuarios">Selecciona un usuario:</label>
    <select name="id_usuarios" required>
        <option value="">-- Elige uno --</option>
        <?php foreach ($usuarios as $fila) { ?>
            <option value="<?php echo htmlspecialchars($fila["id_usuarios"]); ?>"
                <?php if (isset($datos) && $datos["id_usuarios"] == $fila["id_usuarios"]) echo "selected"; ?>>
                <?php echo htmlspecialchars($fila["usuario"]); ?>
            </option>
        <?php } ?>
    </select>
    <input type="submit" name="seleccionar" value="Editar">
</form>

<?php if (isset($datos)) { ?>
    <form method="post">
        <input type="hidden" name="id_usuarios" value="<?php echo htmlspecialchars($datos["id_usuarios"]); ?>">

        <label>Correo:</label>
        <input type="email" name="correo" value="<?php echo htmlspecialchars($datos["correo"]); ?>" required>

        <label>Clave:</label>
        <input type="text" name="clave" value="<?php echo htmlspecialchars($datos["clave"]); ?>" required>
        <label>Fecha de Nacimeinto:</label>
        <?php
        $fechaNac = isset($datos['fecha_nacimiento']) ? htmlspecialchars($datos['fecha_nacimiento']) : '';?>
        <input type="date" name="fechaNac" value="<?= $fechaNac?>" required>
        <label for="rol">Rol:</label>
        <select name="rol">
            <option value="administrador" <?php if ($datos["rol"] == "administrador") echo "selected"; ?>>Administrador</option>
            <option value="lector" <?php if ($datos["rol"] == "lector") echo "selected"; ?>>Lector</option>
            <option value="bibliotecario" <?php if ($datos["rol"] == "bibliotecario") echo "selected"; ?>>Bibliotecario</option>
        </select>

        <input type="submit" name="guardar" value="Guardar Cambios">
    </form>
<?php } ?>

<?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>


    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='gestionar_usuarios.php'">
    </div>
</body>
</html>
