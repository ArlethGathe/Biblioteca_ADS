<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el rol está en la sesión
$usuario_rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'lector'; // Definir rol por defecto como 'lector'

// Obtener géneros y clasificaciones
$generos = $pdo->query("SELECT * FROM generos")->fetchAll();
$clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();

$errores = [];
$success = '';

// Procesar el formulario de agregar libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo'])) {
    // Si estamos agregando un nuevo libro
    if ($_POST['tipo'] === 'libro') {
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

        // Si no hay errores, insertar el libro
        if (empty($errores)) {
            $sql_insert = "INSERT INTO libros (titulo, autor, editorial, edicion, cantidad, estado, genero_id, clasificacion_id)
                           VALUES (:titulo, :autor, :editorial, :edicion, :cantidad, :estado, :genero_id, :clasificacion_id)";
            $stmt = $pdo->prepare($sql_insert);
            $stmt->execute([
                ':titulo' => $titulo,
                ':autor' => $autor,
                ':editorial' => $editorial,
                ':edicion' => $edicion,
                ':cantidad' => $cantidad,
                ':estado' => $estado,
                ':genero_id' => $genero_id,
                ':clasificacion_id' => $clasificacion_id
            ]);

            $success = 'Libro agregado correctamente.';
        }
    }

    // Si estamos agregando un nuevo género
    if ($_POST['tipo'] === 'genero') {
        $nuevo_genero = trim($_POST['nuevo_genero']);
        
        // Verificar si el género ya existe
        $check_genero = $pdo->prepare("SELECT * FROM generos WHERE nombre = :nombre");
        $check_genero->execute([':nombre' => $nuevo_genero]);
        if ($check_genero->rowCount() > 0) {
            $errores[] = 'El género ya existe.';
        } else {
            // Insertar el nuevo género
            if (!empty($nuevo_genero)) {
                $sql_insert_genero = "INSERT INTO generos (nombre) VALUES (:nombre)";
                $stmt = $pdo->prepare($sql_insert_genero);
                $stmt->execute([':nombre' => $nuevo_genero]);

                $success = 'Género agregado correctamente.';
                // Actualizamos los géneros para incluir el recién agregado
                $generos = $pdo->query("SELECT * FROM generos")->fetchAll();
            }
        }
    }

    // Si estamos agregando una nueva clasificación
    if ($_POST['tipo'] === 'clasificacion') {
        $nueva_clasificacion = trim($_POST['nueva_clasificacion']);
        
        // Verificar si la clasificación ya existe
        $check_clasificacion = $pdo->prepare("SELECT * FROM clasificaciones WHERE nombre = :nombre");
        $check_clasificacion->execute([':nombre' => $nueva_clasificacion]);
        if ($check_clasificacion->rowCount() > 0) {
            $errores[] = 'La clasificación ya existe.';
        } else {
            // Insertar la nueva clasificación
            if (!empty($nueva_clasificacion)) {
                $sql_insert_clasificacion = "INSERT INTO clasificaciones (nombre) VALUES (:nombre)";
                $stmt = $pdo->prepare($sql_insert_clasificacion);
                $stmt->execute([':nombre' => $nueva_clasificacion]);

                $success = 'Clasificación agregada correctamente.';
                // Actualizamos las clasificaciones para incluir la recién agregada
                $clasificaciones = $pdo->query("SELECT * FROM clasificaciones")->fetchAll();
            }
        }
    }
}

// Agregar nuevo género
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

    <!-- Formulario de agregar libro -->
    <form method="POST" action="">
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
