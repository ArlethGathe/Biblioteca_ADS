<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el bibliotecario está logueado
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'bibliotecario') {
    die('Acceso denegado.');
}

if (isset($_GET['prestamo_id'])) {
    $prestamo_id = $_GET['prestamo_id'];
    $sql = "UPDATE prestamos SET estado = 'aprobado', fecha_prestamo = NOW() WHERE id = :prestamo_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':prestamo_id' => $prestamo_id]);

    echo "Préstamo registrado correctamente.";
}
?>
