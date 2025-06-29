<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el ID del libro fue pasado
if (!isset($_GET['id'])) {
    die('ID de libro no especificado.');
}

$libro_id = $_GET['id'];

// Obtener los datos del libro
$sql = "SELECT * FROM libros WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $libro_id]);
$libro = $stmt->fetch();

// Verificar si el libro existe
if (!$libro) {
    die('El libro no existe.');
}

// Obtener géneros y clasificaciones
$generos = $pdo->query("SELECT * FROM generos")->fetchAll();
$clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();

$errores = [];
$success = '';

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $editorial = trim($_POST['editorial']);
    $edicion = trim($_POST['edicion']);
    $cantidad = (int) $_POST['cantidad'];
    $estado = $_POST['estado'];
    $genero_id = $_POST['genero_id'];
    $clasificacion_id = $_POST['clasificacion_id'];

    // Validar que todos los campos estén completos
    if (empty($titulo) || empty($autor) || empty($editorial)) {
        $errores[] = 'Los campos Título, Autor y Editorial son obligatorios.';
    }

    // Si no hay errores, actualizar el libro
    if (empty($errores)) {
        $sql_update = "UPDATE libros SET
            titulo = :titulo,
            autor = :autor,
            editorial = :editorial,
            edicion = :edicion,
            cantidad = :cantidad,
            estado = :estado,
            genero_id = :genero_id,
            clasificacion_id = :clasificacion_id
            WHERE id = :id";

        $stmt = $pdo->prepare($sql_update);
        $stmt->execute([
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':editorial' => $editorial,
            ':edicion' => $edicion,
            ':cantidad' => $cantidad,
            ':estado' => $estado,
            ':genero_id' => $genero_id,
            ':clasificacion_id' => $clasificacion_id,
            ':id' => $libro_id
        ]);

        $success = 'Libro actualizado correctamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos adicionales para que coincidan con la paleta de colores */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-submit {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #00408e;
        }

        .alert-error {
            color: red;
            font-size: 14px;
        }

        .alert-success {
            color: green;
            font-size: 14px;
        }

        .btn-back {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Libro: <?php echo htmlspecialchars($libro['titulo']); ?></h2>

    <!-- Botón de Volver -->
    <a href="home_usuario.php" class="btn-back">&larr; Volver al panel</a>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if ($success): ?>
        <p class="alert-success"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if ($errores): ?>
        <ul class="alert-error">
            <?php foreach ($errores as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Formulario de edición -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
        </div>
        <div class="form-group">
            <label for="autor">Autor *</label>
            <input type="text" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
        </div>
        <div class="form-group">
            <label for="editorial">Editorial *</label>
            <input type="text" name="editorial" value="<?php echo htmlspecialchars($libro['editorial']); ?>" required>
        </div>
        <div class="form-group">
            <label for="edicion">Edición</label>
            <input type="text" name="edicion" value="<?php echo htmlspecialchars($libro['edicion']); ?>">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" value="<?php echo htmlspecialchars($libro['cantidad']); ?>" min="1">
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado">
                <option value="disponible" <?php echo ($libro['estado'] === 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                <option value="prestado" <?php echo ($libro['estado'] === 'prestado') ? 'selected' : ''; ?>>Prestado</option>
                <option value="reservado" <?php echo ($libro['estado'] === 'reservado') ? 'selected' : ''; ?>>Reservado</option>
            </select>
        </div>
        <div class="form-group">
            <label for="genero_id">Género</label>
            <select name="genero_id" required>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?php echo $genero['id']; ?>" <?php echo ($libro['genero_id'] == $genero['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($genero['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="clasificacion_id">Clasificación</label>
            <select name="clasificacion_id" required>
                <?php foreach ($clasificaciones as $clasificacion): ?>
                    <option value="<?php echo $clasificacion['id']; ?>" <?php echo ($libro['clasificacion_id'] == $clasificacion['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($clasificacion['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-submit">Guardar cambios</button>
    </form>
</div>

</body>
</html>
