<?php
include("db.php");

$mensaje = '';
$idSeleccionada = $_POST["id_multa_select"] ?? null;

// Obtener todas las multas para mostrar en el <select>
$multasDisponibles = $pdo->query("SELECT id_multa, usuario, titulo FROM multas")->fetchAll(PDO::FETCH_ASSOC);

// Si se envió una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar"])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM multas WHERE id_multa = :id");
        $stmt->execute([':id' => $idSeleccionada]);

        $mensaje = "Multa eliminada correctamente.";

        // Limpiar la selección después de eliminar
        $idSeleccionada = null;
        $multasDisponibles = $pdo->query("SELECT id_multa, usuario, titulo FROM multas")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar la multa: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Multa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Eliminar Multa</h2>

    <!-- Formulario para seleccionar multa a eliminar -->
    <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta multa? Esta acción no se puede deshacer.')">
        <label for="id_multa_select">Selecciona una multa:</label>
        <select name="id_multa_select" required>
            <option value="">Selecciona una multa</option>
            <?php foreach ($multasDisponibles as $m): ?>
                <option value="<?= $m['id_multa'] ?>" <?= ($idSeleccionada == $m['id_multa']) ? 'selected' : '' ?>>
                    <?= "ID {$m['id_multa']} - {$m['usuario']} - {$m['titulo']}" ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="eliminar" value="1">
        <input type="submit" value="Eliminar Multa" style="background-color: red; color: white;">
    </form>

    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='multas.php'">
    </div>

    <?php if ($mensaje) echo "<p style='text-align:center;'>$mensaje</p>"; ?>
</body>
</html>
