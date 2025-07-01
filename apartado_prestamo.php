

<?php
// Este proceso debe permitir que un lector reserve el libro por un plazo determinado,
//  y si no recoge el libro en 24 horas, el apartado se cancela
session_start();
require __DIR__ . '/db.php';

// Verificar si el lector está logueado
if (!isset($_SESSION['usuario'])) {
    die('No estás autenticado para realizar esta acción.');
}

if (isset($_GET['libro_id'])) {
    $libro_id = $_GET['libro_id'];
    $lector_id = $_SESSION['id'];
    $fecha_apartado = date('Y-m-d H:i:s');
    $fecha_limite = date('Y-m-d H:i:s', strtotime('+24 hours'));

    // Registrar apartado en la base de datos
    $sql = "INSERT INTO apartados (libro_id, lector_id, fecha_apartado, fecha_limite) 
            VALUES (:libro_id, :lector_id, :fecha_apartado, :fecha_limite)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':libro_id' => $libro_id,
        ':lector_id' => $lector_id,
        ':fecha_apartado' => $fecha_apartado,
        ':fecha_limite' => $fecha_limite
    ]);

    echo "Libro apartado correctamente. Tienes 24 horas para recogerlo.";
}
?>
