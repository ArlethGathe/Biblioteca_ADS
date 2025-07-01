<?php
session_start();
require __DIR__ . '/db.php';

$usuario_rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'lector';

$generos = $pdo->query("SELECT * FROM generos")->fetchAll();
$clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();

$errores = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo'])) {
    if ($_POST['tipo'] === 'libro') {
        $titulo = trim($_POST['titulo']);
        $autor = trim($_POST['autor']);
        $editorial = trim($_POST['editorial']);
        $edicion = trim($_POST['edicion']);
        $cantidad = (int) $_POST['cantidad'];
        $estado = $_POST['estado'];
        $genero_id = $_POST['genero_id'];
        $clasificacion_id = $_POST['clasificacion_id'];

        // Procesar portada
        $ruta_portada = 'portadas/default.png'; // por defecto
        if (isset($_FILES['portada']) && $_FILES['portada']['error'] === 0) {
            $nombreArchivo = basename($_FILES["portada"]["name"]);
            $nombreFinal = time() . '_' . $nombreArchivo;
            $rutaDestino = 'portadas/' . $nombreFinal;
            if (move_uploaded_file($_FILES["portada"]["tmp_name"], $rutaDestino)) {
                $ruta_portada = $rutaDestino;
            }
        }

        if (empty($titulo) || empty($autor) || empty($editorial)) {
            $errores[] = 'Los campos Título, Autor y Editorial son obligatorios.';
        }

        if (empty($errores)) {
            $sql_insert = "INSERT INTO libros (titulo, autor, editorial, edicion, cantidad, estado, genero_id, clasificacion_id, portada)
                           VALUES (:titulo, :autor, :editorial, :edicion, :cantidad, :estado, :genero_id, :clasificacion_id, :portada)";
            $stmt = $pdo->prepare($sql_insert);
            $stmt->execute([
                ':titulo' => $titulo,
                ':autor' => $autor,
                ':editorial' => $editorial,
                ':edicion' => $edicion,
                ':cantidad' => $cantidad,
                ':estado' => $estado,
                ':genero_id' => $genero_id,
                ':clasificacion_id' => $clasificacion_id,
                ':portada' => $ruta_portada
            ]);

            $success = 'Libro agregado correctamente.';
        }
    }

    if ($_POST['tipo'] === 'genero') {
        $nuevo_genero = trim($_POST['nuevo_genero']);
        $check_genero = $pdo->prepare("SELECT * FROM generos WHERE nombre = :nombre");
        $check_genero->execute([':nombre' => $nuevo_genero]);
        if ($check_genero->rowCount() > 0) {
            $errores[] = 'El género ya existe.';
        } else {
            if (!empty($nuevo_genero)) {
                $sql_insert_genero = "INSERT INTO generos (nombre) VALUES (:nombre)";
                $stmt = $pdo->prepare($sql_insert_genero);
                $stmt->execute([':nombre' => $nuevo_genero]);

                $success = 'Género agregado correctamente.';
                $generos = $pdo->query("SELECT * FROM generos")->fetchAll();
            }
        }
    }

    if ($_POST['tipo'] === 'clasificacion') {
        $nueva_clasificacion = trim($_POST['nueva_clasificacion']);
        $check_clasificacion = $pdo->prepare("SELECT * FROM clasificaciones WHERE nombre = :nombre");
        $check_clasificacion->execute([':nombre' => $nueva_clasificacion]);
        if ($check_clasificacion->rowCount() > 0) {
            $errores[] = 'La clasificación ya existe.';
        } else {
            if (!empty($nueva_clasificacion)) {
                $sql_insert_clasificacion = "INSERT INTO clasificaciones (nombre) VALUES (:nombre)";
                $stmt = $pdo->prepare($sql_insert_clasificacion);
                $stmt->execute([':nombre' => $nueva_clasificacion]);

                $success = 'Clasificación agregada correctamente.';
                $clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Libro</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="estilo_moreL.css">
</head>
<body>

<div class="container">
    <h2>Agregar Nuevo Libro</h2>

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

    <!-- Formulario para agregar libro con portada -->
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="autor">Autor *</label>
            <input type="text" name="autor" required>
        </div>
        <div class="form-group">
            <label for="editorial">Editorial *</label>
            <input type="text" name="editorial" required>
        </div>
        <div class="form-group">
            <label for="edicion">Edición</label>
            <input type="text" name="edicion">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" min="1" required>
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" required>
                <option value="disponible">Disponible</option>
                <option value="prestado">Prestado</option>
                <option value="reservado">Reservado</option>
            </select>
        </div>
        <div class="form-group">
            <label for="genero_id">Género</label>
            <select name="genero_id" required>
                <option value="">-- Ninguno --</option>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?php echo $genero['id']; ?>"><?php echo htmlspecialchars($genero['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="clasificacion_id">Clasificación</label>
            <select name="clasificacion_id" required>
                <option value="">-- Ninguno --</option>
                <?php foreach ($clasificaciones as $clasificacion): ?>
                    <option value="<?php echo $clasificacion['id']; ?>"><?php echo htmlspecialchars($clasificacion['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="portada">Portada del libro</label>
            <input type="file" name="portada" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Guardar libro</button>
        <input type="hidden" name="tipo" value="libro">
    </form>

    <!-- Agregar nuevo género -->
    <h3>Agregar nuevo género</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nuevo_genero">Nuevo Género</label>
            <input type="text" name="nuevo_genero" placeholder="Ingrese el nombre del nuevo género" required>
        </div>
        <button type="submit" class="btn-submit">Agregar Género</button>
        <input type="hidden" name="tipo" value="genero">
    </form>

    <!-- Agregar nueva clasificación -->
    <h3>Agregar nueva clasificación</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nueva_clasificacion">Nueva Clasificación</label>
            <input type="text" name="nueva_clasificacion" placeholder="Ingrese el nombre de la nueva clasificación" required>
        </div>
        <button type="submit" class="btn-submit">Agregar Clasificación</button>
        <input type="hidden" name="tipo" value="clasificacion">
    </form>
</div>

</body>
</html>
