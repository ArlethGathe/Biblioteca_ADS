<?php
// db.php — Conexión PDO a la base de datos "biblioteca"

$host     = 'localhost';  // Cambiar de 127.0.0.1 a localhost
$db       = 'biblioteca';
$user     = 'root';
$password = '';           
$charset  = 'utf8mb4';
$port     = 3307;  

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
   
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    exit;
}
?>
