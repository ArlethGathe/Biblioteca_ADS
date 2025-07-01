<?php
session_start();
require __DIR__ . '/db.php';

// Obtener ID del libro desde el parámetro de URL
$libro_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($libro_id > 0) {
    // Obtener detalles del libro
    $sql = "SELECT * FROM libros WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $libro_id]);
    $libro = $stmt->fetch();

    if (!$libro) {
        // Si no existe el libro
        die('El libro no existe.');
    }

    // Verificar si hay existencias disponibles
    if ($libro['cantidad'] <= 0) {
        die('No hay existencias disponibles de este libro.');
    }

    // Obtener el género y clasificación asociados con el libro
    $genero_id = $libro['genero_id'];
    $clasificacion_id = $libro['clasificacion_id'];

    // Consultar los géneros y clasificaciones
    $generos = $pdo->query("SELECT * FROM generos")->fetchAll();
    $clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();
} else {
    die('ID de libro inválido.');
}

$errores = [];
$success = '';

// Procesar la solicitud de préstamo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el lector ha iniciado sesión
    if (!isset($_SESSION['usuario'])) {
        $errores[] = 'No estás autenticado para realizar esta acción.';
    }

    // Obtener el ID del lector desde la sesión
    $lector_id = $_SESSION['usuario'];  // Cambiado a 'usuario' para que coincida con 'id_usuarios'

    // Verificar que hay libros disponibles antes de procesar la solicitud
    if ($libro['cantidad'] > 0) {
        // Actualizar la cantidad de libros
        $sql_update = "UPDATE libros SET cantidad = cantidad - 1, estado = 'prestado' WHERE id = :libro_id";
        $stmt = $pdo->prepare($sql_update);
        $stmt->execute([':libro_id' => $libro_id]);

        // Insertar el préstamo en la base de datos
        $sql = "INSERT INTO prestamos (libro_id, lector_id, fecha_solicitud, estado) 
                VALUES (:libro_id, :lector_id, NOW(), 'pendiente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':libro_id' => $libro_id,
            ':lector_id' => $lector_id  // Usar el id_usuarios de la sesión
        ]);

        $success = 'Préstamo solicitado correctamente.';
    } else {
        $errores[] = 'No hay libros disponibles para préstamo en este momento.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Préstamo</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #003366;
            text-align: center;
        }

        .book-info {
            text-align: center;
            margin: 20px 0;
        }

        .book-info img {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
        }

        .btn-submit {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        .btn-submit:hover {
            background-color: #00408e;
        }

        .alert-success {
            color: green;
            text-align: center;
        }

        .alert-error {
            color: red;
            text-align: center;
        }

        .btn-back {
            background-color: #003060;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: block;
            margin: 20px auto;
            width: 20%;
        }

        .btn-back:hover {
            background-color: #d32f2f;
        }
        #solPre {
            margin: auto;
            background-color: none;
            padding: 0px;
            border: none;
            border-radius: 0px;
            box-shadow: 0 0 0px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Solicitar Préstamo de Libro</h2>

    <!-- Mostrar errores si los hay -->
    <?php if (!empty($errores)): ?>
        <div class="alert-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="alert-success"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Información del libro -->
    <div class="book-info">
        <?php if (!empty($libro['portada'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($libro['portada']); ?>" alt="Portada del libro">
        <?php else: ?>
            <img src="default-cover.png" alt="Portada del libro">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
        <p><?php echo htmlspecialchars($libro['autor']); ?></p>
        <p>Cantidad: <?php echo $libro['cantidad']; ?></p>
        <p>Estado: <?php echo $libro['estado']; ?></p>
        <p>Género: <?php echo $generos[$genero_id - 1]['nombre']; ?></p>
        <p>Clasificación: <?php echo $clasificaciones[$clasificacion_id - 1]['nombre']; ?></p>
    </div>

    <!-- Botón para solicitar préstamo -->
    <form method="POST" id="solPre">
        <button type="submit" class="btn-submit">Solicitar Préstamo</button>
    </form>

    <!-- Volver al catálogo -->
    <input type="button" value="Volver al Catálogo" class="btn-back" onclick="window.location.href='home_usuario.php'">
    
</div>

</body>
</html>
