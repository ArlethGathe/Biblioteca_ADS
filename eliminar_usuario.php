<?php

include("db.php");

// Obtener usuarios disponibles (para el select)
$stmt = $pdo->query("SELECT id_usuarios, usuario FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se envía el formulario para eliminar
if (isset($_POST["eliminar"])) {
    $id = $_POST["id_usuarios"];

    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuarios = ?");
        $stmt->execute([$id]);
        $mensaje = "Usuario eliminado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar: " . $e->getMessage();
    }

    // Recargar usuarios actualizados
    $stmt = $pdo->query("SELECT id_usuarios, usuario FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <label for="id_usuarios">Selecciona un usuario para eliminar:</label>
    <select name="id_usuarios" required>
        <option value="">-- Elige uno --</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= htmlspecialchars($u["id_usuarios"]) ?>">
                <?= htmlspecialchars($u["usuario"]) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="eliminar" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
        Eliminar
    </button>
</form>

<?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="gestionar_usuarios.php">← Volver a gestión</a>
    </div>
</body>
</html>
