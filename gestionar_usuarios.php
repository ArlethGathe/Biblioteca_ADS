<?php
include("db.php");

// Búsqueda segura
$usuarios = [];
if (isset($_GET['busqueda']) && $_GET['busqueda'] !== "") {
    $busqueda = '%' . $_GET['busqueda'] . '%';

    // Preparar y ejecutar con placeholders
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario LIKE ? OR correo LIKE ?");
    $stmt->execute([$busqueda, $busqueda]);

    // Obtener resultados como arreglo asociativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Consulta directa sin búsqueda
    $stmt = $pdo->query("SELECT * FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="estilo_HomeUsuario.css">
</head>
<body>
    <h2>Gestionar Usuarios</h2>
    <div class="container">
        <div class="acciones">
            <!-- Botón de Agregar libro solo visible para bibliotecarios -->
            <div class="mb-4">
                <a href="agregar_usuario.php" class="btn-add">Agregar Usuario</a>
            </div>
            <div class="mb-4">
                <a href="editar_usuario.php" class="btn-add">Editar Usuario</a>
            </div>
            <div class="mb-4">
                <a href="eliminar_usuario.php" class="btn-add">Eliminar Usuario</a>
            </div>
    </div>

    <div class="contenedor-busqueda">
    <form method="GET" action="">
        <div class="input-con-boton">
            <input type="text" name="busqueda" placeholder="Visualizar por usuario o correo" class="input-busqueda" value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
            <button type="submit" class="boton-lupa" onclick="searchUsuario()">&#128269;</button> <!-- lupa como ícono -->
        </div>
    
    </form>
</div>
    <div>
        <iframe src="listar_usuarios.php" class="listar-usuarios"></iframe>
</div>

    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='home_usuario.php'">
    </div>
    <script>
       function searchUsuario() { 
        const input = document.querySelector('input[name="busqueda"]'); 
        const searchText = input.value.toLowerCase().trim(); 
        const rows = document.querySelectorAll('table tbody tr'); 
        rows.forEach(function(row) { const rowText = row.textContent.toLowerCase(); 
            if (searchText === "") {
                 row.style.display = ""; 
                } else if (rowText.includes(searchText)) { 
                    row.style.display = ""; 
                } else { 
                    row.style.display = "none"; 
                } }); 
    } 
// Opcional: activar búsqueda al presionar Enter document.addEventListener('DOMContentLoaded', function() { const input = document.querySelector('input[name="busqueda"]'); input.addEventListener('keydown', function(event) { if (event.key === 'Enter') { event.preventDefault(); // Evita enviar el formulario si lo hay searchUsuarioTable(); } }); }); 
        
    </script>
</div>
</body>
</html>