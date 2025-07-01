<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el bibliotecario está logueado y que el rol sea 'bibliotecario'
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'bibliotecario') {
    die('Acceso denegado.');
}

// Consulta SQL para obtener los préstamos pendientes
$sql = "SELECT prestamos.*, libros.titulo, libros.autor, usuarios.usuario AS lector
        FROM prestamos JOIN libros ON prestamos.libro_id = libros.id
        JOIN usuarios ON prestamos.lector_id = usuarios.id_usuarios
        WHERE prestamos.estado = 'pendiente'";

try {
    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $prestamos = $stmt->fetchAll();

    // Depuración: Verificar si la consulta devuelve resultados
    if (!$prestamos) {
        echo "No se encontraron préstamos pendientes.";
    }
} catch (PDOException $e) {
    // Mostrar error si la consulta falla
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Préstamos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h1>Solicitudes de Préstamo Pendientes</h1>

    <?php if (count($prestamos) > 0): ?>
        <?php foreach ($prestamos as $prestamo): ?>
            <div class="prestamo-item">
                <h4><?php echo htmlspecialchars($prestamo['titulo']); ?> - <?php echo htmlspecialchars($prestamo['autor']); ?></h4>
                <p><strong>Lector:</strong> <?php echo htmlspecialchars($prestamo['lector']); ?></p>
                <p><strong>Fecha de Solicitud:</strong> <?php echo $prestamo['fecha_solicitud']; ?></p>

                <!-- Botones para aprobar o rechazar el préstamo -->
                <a href="procesar_prestamo.php?id=<?php echo $prestamo['id']; ?>&accion=aprobar" class="btn-approve">Aprobar Préstamo</a>
                <a href="procesar_prestamo.php?id=<?php echo $prestamo['id']; ?>&accion=rechazar" class="btn-reject">Rechazar Préstamo</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay solicitudes de préstamos pendientes en este momento.</p>
    <?php endif; ?>
</div>

</body>
</html>
