<?php
include("db.php");


$usuarios = $pdo->query("SELECT usuario FROM usuarios WHERE rol = 'lector'");

$titulos = $pdo->query("SELECT  titulo FROM libros");
$fechaVenS = $pdo->query("SELECT fecha_vencimiento FROM prestamos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id_multa"] ?? null; // ID del usuario, si se está editando
    $usuario = $_POST["usuario"];        // ID del usuario
    $titulo = $_POST["titulo"];          // ID del título
    $fechaNac = $_POST["fecha_vence"];  
    $cantidadP = $_POST["deuda"];
    $descripcion = $_POST["descripcion"];


    try {
    $stmt = $pdo->prepare("INSERT INTO multas (usuario, titulo, fecha_vencimiento, cantidad_pesos, descripcion) 
                           VALUES (:usuario, :titulo, :fecha_vence, :deuda, :descripcion)");
    $stmt->execute([
        ':usuario' => $usuario,
        ':titulo' => $titulo,
        ':fecha_vence' => $fechaNac,
        ':deuda' => $cantidadP,
        ':descripcion' => $descripcion
    ]);
    $mensaje = "Multa agregada correctamente.";
} catch (PDOException $e) {
    $mensaje = "Error al agregar la multa: " . $e->getMessage();
}

}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Multa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Agregar Multa</h2>
    <form method="post">
        <label for="id_Multa">ID Multa:</label>
        <!-- Usuario -->
        <label for="usuario">Usuario:</label>
        <select name="usuario" required>
        <option value="">Selecciona un usuario</option>
            <?php while ($u = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $u['usuario'] ?>"><?= htmlspecialchars($u['usuario']) ?></option>
            <?php endwhile; ?>
         </select>
          <!-- Título -->
        <label for="titulo">Título:</label>
        <select name="titulo" required>
            <option value="">Selecciona un título</option>
            <?php while ($t = $titulos->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $t['titulo'] ?>"><?= htmlspecialchars($t['titulo']) ?></option>
            <?php endwhile; ?>
        </select>

        <!-- Fecha -->
        <label for="fecha_vence">Fecha de vencimiento:</label>

        <select name="fecha_vence" >
            <option value="">Selecciona una fecha</option>
            <?php while ($f = $fechaVenS->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $f['fecha_vencimiento'] ?>"><?= htmlspecialchars($f['fecha_vencimiento']) ?></option>

            <?php endwhile; ?>
        </select>
        <label for="cantidad_Pesos">Deuda:</label>
        <input type="number" min="0" max="1000" step="100" name="deuda"  required>
        <input type="text" name="descripcion" placeholder="Descripcion de la multa" required>
        <input type="submit" value="Agregar Multa">
    </form>

    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='multas.php'">
    </div>

    <?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>
</body>
</html>
