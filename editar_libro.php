<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_GET['id'])) {
    die('ID de libro no especificado.');
}

$libro_id = $_GET['id'];

// Corregir la consulta para usar 'id_libro' en lugar de 'id'
$sql = "SELECT * FROM libros WHERE id_libro = :id_libro";  // Cambié 'id' por 'id_libro'
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_libro' => $libro_id]);  // Asegúrate de usar ':id_libro' en lugar de ':id'
$libro = $stmt->fetch();

if (!$libro) {
    die('El libro no existe.');
}

$generos = $pdo->query("SELECT * FROM generos")->fetchAll();
$clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();

$errores = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $editorial = trim($_POST['editorial']);
    $edicion = trim($_POST['edicion']);
    $cantidad = (int) $_POST['cantidad'];
    $estado = $_POST['estado'];
    $genero_id = $_POST['genero_id'];
    $clasificacion_id = $_POST['clasificacion_id'];
    $portada_actual = $libro['portada'];

    // Manejo de subida de portada nueva
    $portada = $portada_actual;
    if (!empty($_FILES['portada']['name'])) {
        $nombre_archivo = basename($_FILES['portada']['name']);
        $ruta_archivo = __DIR__ . "/portadas/" . $nombre_archivo;

        if (move_uploaded_file($_FILES['portada']['tmp_name'], $ruta_archivo)) {
            $portada = $nombre_archivo;
        } else {
            $errores[] = 'Error al subir la nueva portada.';
        }
    }

    if (empty($titulo) || empty($autor) || empty($editorial)) {
        $errores[] = 'Los campos Título, Autor y Editorial son obligatorios.';
    }

    if (empty($errores)) {
        // Corregir la consulta para usar 'id_libro' en lugar de 'id'
        $sql_update = "UPDATE libros SET
            titulo = :titulo,
            autor = :autor,
            editorial = :editorial,
            edicion = :edicion,
            cantidad = :cantidad,
            estado = :estado,
            genero_id = :genero_id,
            clasificacion_id = :clasificacion_id,
            portada = :portada
            WHERE id_libro = :id_libro";  // Cambié 'id' por 'id_libro'

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
            ':portada' => $portada,
            ':id_libro' => $libro_id  // Asegúrate de usar ':id_libro'
        ]);

        $success = 'Libro actualizado correctamente.';
        // Refrescar datos
        $libro['portada'] = $portada;
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            color: #003366;
            text-align: center;
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
        }
        .btn-submit {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .alert-error {
            color: red;
        }
        .alert-success {
            color: green;
        }
        .btn-back {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Libro: <?php echo htmlspecialchars($libro['titulo']); ?></h2>

    <a href="home_usuario.php" class="btn-back">&larr; Volver al panel</a>

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

    <form method="POST" enctype="multipart/form-data">
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
                    <option value="<?php echo $genero['id_genero']; ?>" <?php echo ($libro['genero_id'] == $genero['id_genero']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($genero['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="clasificacion_id">Clasificación</label>
            <select name="clasificacion_id" required>
                <?php foreach ($clasificaciones as $clasificacion): ?>
                    <option value="<?php echo $clasificacion['id_clasificacion']; ?>" <?php echo ($libro['clasificacion_id'] == $clasificacion['id_clasificacion']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($clasificacion['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="portada">Portada (dejar vacío para mantener la actual)</label>
            <input type="file" name="portada" accept="image/*">
            <?php if (!empty($libro['portada'])): ?>
                <p>Portada actual: <strong><?php echo htmlspecialchars($libro['portada']); ?></strong></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Guardar cambios</button>
    </form>
</div>

</body>
</html>
