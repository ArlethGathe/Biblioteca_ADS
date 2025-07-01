<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el bibliotecario está logueado
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'bibliotecario') {
    die('Acceso denegado.');
}



// Verificar que el parámetro 'id' y 'accion' estén presentes en la URL
if (!isset($_GET['id']) || !isset($_GET['accion'])) {
    die('ID de préstamo o acción no especificados.');
}

$prestamo_id = $_GET['id'];  // Usamos 'id' en lugar de 'prestamo_id'
$accion = $_GET['accion'];

// Consultar los detalles del préstamo
$sql = "SELECT * FROM prestamos WHERE id = :prestamo_id";  // Cambiar 'prestamo_id' por 'id'
$stmt = $pdo->prepare($sql);
$stmt->execute([':prestamo_id' => $prestamo_id]);
$prestamo = $stmt->fetch();

// Verificar si el préstamo existe
if (!$prestamo) {
    die('Préstamo no encontrado.');
}

// Procesar la acción: Aprobar o Rechazar
if ($accion == 'aprobar') {
    // Actualizar el estado del préstamo a 'activo'
    $sql_update = "UPDATE prestamos SET estado = 'activo', fecha_inicio = NOW(), fecha_vencimiento = DATE_ADD(NOW(), INTERVAL 15 DAY) WHERE id = :prestamo_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([':prestamo_id' => $prestamo_id]);

    // Actualizar el estado del libro a 'prestado'
    $sql_update_libro = "UPDATE libros SET cantidad = cantidad - 1, estado = 'prestado' WHERE id = :libro_id";
    $stmt_update_libro = $pdo->prepare($sql_update_libro);
    $stmt_update_libro->execute([':libro_id' => $prestamo['libro_id']]);

    echo "El préstamo ha sido aprobado exitosamente.";
} elseif ($accion == 'rechazar') {
    // Actualizar el estado del préstamo a 'rechazado'
    $sql_update = "UPDATE prestamos SET estado = 'rechazado' WHERE id = :prestamo_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([':prestamo_id' => $prestamo_id]);

    // Devolver el libro a la disponibilidad
    $sql_update_libro = "UPDATE libros SET cantidad = cantidad + 1 WHERE id = :libro_id";
    $stmt_update_libro = $pdo->prepare($sql_update_libro);
    $stmt_update_libro->execute([':libro_id' => $prestamo['libro_id']]);

    echo "El préstamo ha sido rechazado.";
} else {
    die('Acción no válida.');
}

// Redirigir de vuelta a la página de verificar préstamos
header('Location: verificar_prestamo.php');
exit;
