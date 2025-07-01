<?php
include("db.php");

$mensaje = '';
$idSeleccionada = $_POST["id_multa_select"] ?? null;

// Obtener todas las multas para el <select>
$multasDisponibles = $pdo->query("SELECT id_multa, usuario, titulo FROM multas")->fetchAll(PDO::FETCH_ASSOC);

// Obtener listas para los select de usuarios, títulos y fechas
$usuarios = $pdo->query("SELECT usuario FROM usuarios");
$titulos = $pdo->query("SELECT titulo FROM libros");
$fechaVenS = $pdo->query("SELECT fecha_vencimiento FROM prestamos");

// Si se seleccionó una multa del select, cargar sus datos
$multa = null;
if ($idSeleccionada) {
    $stmt = $pdo->prepare("SELECT * FROM multas WHERE id_multa = :id");
    $stmt->execute([':id' => $idSeleccionada]);
    $multa = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Si se envió el formulario para actualizar (con botón oculto)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
    $usuario = $_POST["usuario"];
    $titulo = $_POST["titulo"];
    $fechaNac = $_POST["fecha_vence"];
    $cantidadP = $_POST["deuda"];
    $descripcion = $_POST["descripcion"];

    try {
        $updateStmt = $pdo->prepare("UPDATE multas 
            SET usuario = :usuario, titulo = :titulo, fecha_vencimiento = :fecha_vence, 
                cantidad_pesos = :deuda, descripcion = :descripcion 
            WHERE id_multa = :id");

        $updateStmt->execute([
            ':usuario' => $usuario,
            ':titulo' => $titulo,
            ':fecha_vence' => $fechaNac,
            ':deuda' => $cantidadP,
            ':descripcion' => $descripcion,
            ':id' => $_POST["id_multa_select"]
        ]);

        $mensaje = "Multa actualizada correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar la multa: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Multa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Editar Multa</h2>

    <!-- Formulario para seleccionar multa -->
    <form method="post">
        <label for="id_multa_select">Selecciona una multa:</label>
        <select name="id_multa_select" required onchange="this.form.submit()">
            <option value="">Selecciona una multa</option>
            <?php foreach ($multasDisponibles as $m): ?>
                <option value="<?= $m['id_multa'] ?>" <?= ($idSeleccionada == $m['id_multa']) ? 'selected' : '' ?>>
                    <?= "ID {$m['id_multa']} - {$m['usuario']} - {$m['titulo']}" ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><input type="submit" value="Cargar Multa"></noscript>
    </form>
    <br>
    <!-- Formulario de edición (solo si ya se seleccionó una multa) -->
    <?php if ($multa): ?>
    <form method="post">
        <input type="hidden" name="id_multa_select" value="<?= $idSeleccionada ?>">
        <input type="hidden" name="actualizar" value="1">

        <!-- Usuario -->
        <label for="usuario">Usuario:</label>
        <select name="usuario" required>
            <option value="">Selecciona un usuario</option>
            <?php while ($u = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $u['usuario'] ?>" <?= $u['usuario'] == $multa['usuario'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['usuario']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Título -->
        <label for="titulo">Título:</label>
        <select name="titulo" required>
            <option value="">Selecciona un título</option>
            <?php while ($t = $titulos->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $t['titulo'] ?>" <?= $t['titulo'] == $multa['titulo'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['titulo']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Fecha -->
        <label for="fecha_vence">Fecha de vencimiento:</label>
        <select name="fecha_vence">
            <option value="">Selecciona una fecha</option>
            <?php while ($f = $fechaVenS->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $f['fecha_vencimiento'] ?>" <?= $f['fecha_vencimiento'] == $multa['fecha_vencimiento'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['fecha_vencimiento']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Deuda y Descripción -->
        <label for="deuda">Deuda:</label>
        <input type="number" min="0" max="1000" step="100" name="deuda" required value="<?= htmlspecialchars($multa['cantidad_pesos']) ?>">

        <input type="text" name="descripcion" required value="<?= htmlspecialchars($multa['descripcion']) ?>">

        <input type="submit" value="Actualizar Multa">
    </form>
    <?php endif; ?>

    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='multas.php'">
    </div>

    <?php if ($mensaje) echo "<p style='text-align:center;'>$mensaje</p>"; ?>
</body>
</html>
