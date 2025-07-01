<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el bibliotecario está logueado
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'bibliotecario') {
    die('Acceso denegado.');
}

// Obtener las solicitudes de préstamos pendientes
$sql = "SELECT prestamos.id_prestamo, prestamos.fecha_solicitud, libros.titulo, libros.autor, usuarios.usuario AS lector
        FROM prestamos
        JOIN libros ON prestamos.libro_id = libros.id_libro
        JOIN usuarios ON prestamos.lector_id = usuarios.id_usuarios
        WHERE prestamos.estado = 'pendiente'";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$prestamos = $stmt->fetchAll();

// Procesar la acción de aprobar o rechazar préstamo
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $accion = $_GET['accion'];
    $prestamo_id = $_GET['id'];

    // Cambiar el estado del préstamo
    if ($accion == 'aprobar') {
        // Actualizar el estado del préstamo a 'activo'
        $update_prestamo = "UPDATE prestamos SET estado = 'activo' WHERE id_prestamo = :prestamo_id";
        $stmt = $pdo->prepare($update_prestamo);
        $stmt->execute([':prestamo_id' => $prestamo_id]);

        // Actualizar el estado del libro a 'prestado'
        $update_libro = "UPDATE libros SET estado = 'prestado' WHERE id_libro = (SELECT libro_id FROM prestamos WHERE id_prestamo = :prestamo_id)";
        $stmt = $pdo->prepare($update_libro);
        $stmt->execute([':prestamo_id' => $prestamo_id]);

    } elseif ($accion == 'rechazar') {
        // Actualizar el estado del préstamo a 'rechazado'
        $update_prestamo = "UPDATE prestamos SET estado = 'rechazado' WHERE id_prestamo = :prestamo_id";
        $stmt = $pdo->prepare($update_prestamo);
        $stmt->execute([':prestamo_id' => $prestamo_id]);

        // Opcional: Puedes cambiar el estado del libro a 'disponible' si el préstamo fue rechazado
        $update_libro = "UPDATE libros SET estado = 'disponible' WHERE id_libro = (SELECT libro_id FROM prestamos WHERE id_prestamo = :prestamo_id)";
        $stmt = $pdo->prepare($update_libro);
        $stmt->execute([':prestamo_id' => $prestamo_id]);
    }

    // Redirigir de nuevo a la página de verificación de préstamos
    header('Location: verificar_prestamo.php');
    exit;
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
        <table>
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Lector</th>
                    <th>Fecha de Solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prestamo['titulo']); ?> - <?php echo htmlspecialchars($prestamo['autor']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['lector']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['fecha_solicitud']); ?></td>
                        <td>
                            <a href="verificar_prestamo.php?id=<?php echo $prestamo['id_prestamo']; ?>&accion=aprobar" class="btn-approve">Aprobar</a>
                            <a href="verificar_prestamo.php?id=<?php echo $prestamo['id_prestamo']; ?>&accion=rechazar" class="btn-reject">Rechazar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay solicitudes de préstamos pendientes en este momento.</p>
    <?php endif; ?>

    <input type="button" value="Volver" class="btn-back" onclick="window.location.href='home_usuario.php'">
</div>

</body>
</html>
