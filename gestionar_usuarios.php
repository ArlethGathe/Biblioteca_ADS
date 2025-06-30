<?php
include("db.php");

// Obtener datos para editar
if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    // Usar prepared statement para evitar inyección SQL
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
    $stmt->bind_param("s", $id); // "s" porque es VARCHAR
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
}

// Guardar cambios
if (isset($_POST["guardar"])) {
    $id = $_POST["id"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $rol = $_POST["rol"];

    // Validación opcional: hashear clave si aún no lo haces
    // $clave = password_hash($clave, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE usuarios SET correo = ?, clave = ?, rol = ? WHERE id_usuarios = ?");
    $stmt->bind_param("ssss", $correo, $clave, $rol, $id);
    $stmt->execute();

    header("Location: editar_usuario.php?msg=editado");
    exit;
}

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
    <style>
        table {
            margin-top: 20px;
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #cce0ff;
            text-align: center;
        }
        th {
            background-color: #004080;
            color: white;
        }
    </style>
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
            <button type="submit" class="boton-lupa">&#128269;</button> <!-- lupa como ícono -->
        </div>
    </form>
</div>


    <?php if (isset($datos)) { ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $datos["id"]; ?>">
        <input type="email" name="correo" value="<?php echo $datos["correo"]; ?>" required>
        <input type="text" name="clave" value="<?php echo $datos["clave"]; ?>" required>
        <select name="rol">
            <option value="Administrador" <?php if ($datos["rol"] == "Administrador") echo "selected"; ?>>Administrador</option>
            <option value="Lector" <?php if ($datos["rol"] == "Lector") echo "selected"; ?>>Lector</option>
            <option value="Bibliotecario" <?php if ($datos["rol"] == "Bibliotecario") echo "selected"; ?>>Bibliotecario</option>
        </select>
        <input type="submit" name="guardar" value="Guardar Cambios">
    </form>
    
    <?php } ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Rol</th>
          
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM usuarios"); 
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $fila["id_usuarios"]; ?></td>
            <td><?php echo $fila["usuario"]; ?></td>
            <td><?php echo $fila["correo"]; ?></td>
            <td><?php echo $fila["rol"]; ?></td>
        </tr>
        <?php } ?>
    </table>

    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='home_usuario.php'">
    </div>
</div>
</body>
</html>