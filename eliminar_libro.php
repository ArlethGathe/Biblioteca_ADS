<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el ID de libro fue pasado por GET
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Eliminar el libro de la base de datos
    $stmt = $pdo->prepare("DELETE FROM libros WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Redirigir al panel de usuario después de eliminar
    header('Location: home_usuario.php');
    exit;
} else {
    die('ID de libro no especificado.');
}
?>
