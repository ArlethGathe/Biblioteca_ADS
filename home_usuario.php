<?php
session_start();
require __DIR__ . '/db.php';

// Verificar si el rol está en la sesión
$usuario_rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'lector';

// Consulta SQL para obtener los libros
if ($usuario_rol === 'bibliotecario') {
    $sql = "SELECT libros.id, libros.titulo, libros.autor, libros.cantidad, libros.estado, 
                   libros.portada,
                   generos.nombre AS genero, clasificaciones.nombre AS clasificacion
            FROM libros
            LEFT JOIN generos ON libros.genero_id = generos.id
            LEFT JOIN clasificaciones ON libros.clasificacion_id = clasificaciones.id
            ORDER BY libros.creado_at DESC";
} else {
    $sql = "SELECT libros.id, libros.titulo, libros.autor, libros.cantidad, libros.estado, 
                   libros.portada,
                   generos.nombre AS genero, clasificaciones.nombre AS clasificacion
            FROM libros
            LEFT JOIN generos ON libros.genero_id = generos.id
            LEFT JOIN clasificaciones ON libros.clasificacion_id = clasificaciones.id
            WHERE libros.estado = 'disponible'
            ORDER BY libros.creado_at DESC";
}

$result = $pdo->query($sql);
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

        <!-- Botones -->
        <a href="logout.php" class="btn-logout">Cerrar sesión</a>
             <?php if ( $usuario_rol === 'lector'): ?>
            <div class="mb-4">
                <a href="perfil.php">
                <button class="boton-perfil">Mi perfil</button></a>
            </div>
        <?php endif; ?>
        
    <div class="acciones">
        
        <?php if ($usuario_rol === 'bibliotecario' || $usuario_rol === 'administrador'): ?>
            <!-- Botón de Agregar libro solo visible para bibliotecarios -->
            <div class="mb-4">
                <a href="agregar_libro.php" class="btn-add">+ Agregar libro</a>
            </div>
            <div class="mb-4">
                <a href="#" class="btn-add">Prestamos</a>
            </div>
            <div class="mb-4">
                <a href="multas.php" class="btn-add">Multas</a>
            </div>
            
        <?php endif; ?>
        <?php if ( $usuario_rol === 'administrador'): ?>
            <div class="mb-4">
                <a href="gestionar_usuarios.php" class="btn-add">Gestionar Usuarios</a>
            </div>
        <?php endif; ?>
        <div class="mb-4">
                <a href="biblioD.php" class="btn-add">Biblioteca Web</a>
            </div>
    </div>
        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <input type="text" id="searchText" placeholder="Buscar por título, autor, etc.">
            <button class="btn-search" onclick="searchBook()">Buscar</button>
        </div>

        <!-- Catálogo de libros -->
        <div class="book-grid">
            <?php if ($result && $result->rowCount() > 0): ?>
                <?php foreach ($libros as $book): ?>
                    <div class="book-card">
                        <?php if (!empty($book['portada']) && file_exists(__DIR__ . '/portadas/' . $book['portada'])): ?>
                            <img src="portadas/<?php echo htmlspecialchars($book['portada']); ?>" alt="Portada de <?php echo htmlspecialchars($book['titulo']); ?>">
                        <?php else: ?>
                            <img src="default-cover.png" alt="Portada por defecto">
                        <?php endif; ?>

                        <h3><?php echo htmlspecialchars($book['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($book['autor']); ?></p>
                        <p>Cantidad: <?php echo $book['cantidad']; ?></p>
                        <p>Estado: <?php echo $book['estado']; ?></p>
                        <p>Género: <?php echo htmlspecialchars($book['genero']); ?></p>
                        <p>Clasificación: <?php echo htmlspecialchars($book['clasificacion']); ?></p>

                        <?php if ($usuario_rol === 'bibliotecario' || $usuario_rol === 'administrador'): ?>
                            <a href="editar_libro.php?id=<?php echo $book['id']; ?>" class="btn-search">Editar</a>
                            <a href="eliminar_libro.php?id=<?php echo $book['id']; ?>" class="btn-search">Eliminar</a>
                        <?php endif; ?>

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

    
</body>
</html>

