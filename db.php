<?php
// db.php — Conexión PDO a la base de datos "biblioteca"

$host     = '127.0.0.1';
$db       = 'libros';
$user     = 'root';
$password = '';           // En XAMPP suele estar vacío
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // Si falla la conexión, mostramos el error y detenemos la ejecución
    echo 'Error de conexión: ' . $e->getMessage();
    exit;
}

