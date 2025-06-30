<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el rol está en la sesión
$usuario_rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'lector'; // Definir rol por defecto como 'lector'

// 1) Consulta SQL para obtener los libros
if ($usuario_rol === 'bibliotecario') {
    // Los bibliotecarios ven todos los libros
    $sql = "SELECT libros.id, libros.titulo, libros.autor, libros.cantidad, libros.estado, 
                   generos.nombre AS genero, clasificaciones.nombre AS clasificacion
            FROM libros
            LEFT JOIN generos ON libros.genero_id = generos.id
            LEFT JOIN clasificaciones ON libros.clasificacion_id = clasificaciones.id
            ORDER BY libros.creado_at DESC";
} else {
    // Los lectores solo ven los libros disponibles
    $sql = "SELECT libros.id, libros.titulo, libros.autor, libros.cantidad, libros.estado, 
                   generos.nombre AS genero, clasificaciones.nombre AS clasificacion
            FROM libros
            LEFT JOIN generos ON libros.genero_id = generos.id
            LEFT JOIN clasificaciones ON libros.clasificacion_id = clasificaciones.id
            WHERE libros.estado = 'disponible'
            ORDER BY libros.creado_at DESC";
}

$result = $pdo->query($sql);

// 2) Fetch all para tener un array con los libros
$libros = $result->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Principal</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="estilo_HomeUsuario.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?> 👋</h2>

        <!-- Botón de Cerrar sesión en la esquina superior derecha -->
        <a href="logout.php" class="btn-logout">Cerrar sesión</a>
        <a href="perfil.php">
        <button class="boton-perfil">Mi perfil</button></a>
    <div class="acciones">
        
        <?php if ($usuario_rol === 'bibliotecario' || $usuario_rol === 'administrador'): ?>
            <!-- Botón de Agregar libro solo visible para bibliotecarios -->
            <div class="mb-4">
                <a href="agregar_libro.php" class="btn-add">+ Agregar libro</a>
            </div>
        <?php endif; ?>
        <?php if ( $usuario_rol === 'administrador'): ?>
            <div class="mb-4">
                <a href="gestionar_usuarios.php" class="btn-add">Gestionar Usuarios</a>
            </div>
        <?php endif; ?>
    </div>
        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <input type="text" id="searchText" placeholder="Buscar por título, autor, etc.">
            <button class="btn-search" onclick="searchBook()">Buscar</button>
        </div>

        <!-- Vista de los libros (Catálogo de todos los libros) -->
        <div class="book-grid">
            <?php if ($result && $result->rowCount() > 0): ?>
                <?php foreach ($libros as $book): ?>
                    <div class="book-card">
                        <?php if (!empty($book['portada'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($book['portada']); ?>" alt="<?php echo htmlspecialchars($book['titulo']); ?>">
                        <?php else: ?>
                            <img src="default-cover.png" alt="Portada por defecto">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($book['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($book['autor']); ?></p>
                        <p>Cantidad: <?php echo $book['cantidad']; ?></p>
                        <p>Estado: <?php echo $book['estado']; ?></p>
                        <p>Género: <?php echo htmlspecialchars($book['genero']); ?></p>
                        <p>Clasificación: <?php echo htmlspecialchars($book['clasificacion']); ?></p>

                        <!-- Los botones de edición y eliminación son solo para bibliotecarios -->
                        <?php if ($usuario_rol === 'bibliotecario' || $usuario_rol === 'administrador'): ?>
                            <a href="editar_libro.php?id=<?php echo $book['id']; ?>" class="btn-search">Editar</a>
                            <a href="eliminar_libro.php?id=<?php echo $book['id']; ?>" class="btn-search">Eliminar</a>
                        <?php endif; ?>

                        <!-- Botón para solicitar préstamo solo visible para lectores -->
                        <?php if ($usuario_rol === 'lector' && $book['estado'] === 'disponible'): ?>
                            <a href="solicitar_prestamo.php?id=<?php echo $book['id']; ?>" class="btn-search">Solicitar préstamo</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; width:100%;">No hay libros disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Función de búsqueda
        function searchBook() {
            var searchText = document.getElementById('searchText').value.toLowerCase();
            var books = document.querySelectorAll('.book-card');

            books.forEach(function(book) {
                var title = book.querySelector('h3').textContent.toLowerCase();
                var author = book.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchText) || author.includes(searchText)) {
                    book.style.display = 'block';
                } else {
                    book.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
