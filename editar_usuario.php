<?php
include("db.php");

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];
    $datos = $conexion->query("SELECT * FROM usuarios WHERE id = $id")->fetch_assoc();
}

if (isset($_POST["guardar"])) {
    $id = $_POST["id"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $rol = $_POST["rol"];

    $sql = "UPDATE usuarios SET correo='$correo', clave='$clave', rol='$rol' WHERE id=$id";
    $conexion->query($sql);
    header("Location: editar_usuario.php?msg=editado");
}

$condicion = "";
if (isset($_GET['busqueda']) && $_GET['busqueda'] !== "") {
    $busqueda = $conexion->real_escape_string($_GET['busqueda']);
    $condicion = "WHERE usuario LIKE '%$busqueda%' OR correo LIKE '%$busqueda%'";
}

$usuarios = $conexion->query("SELECT * FROM usuarios $condicion");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
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
    <h2>Editar Usuario</h2>

    <div class="contenedor-busqueda">
    <form method="GET" action="">
        <div class="input-con-boton">
            <input type="text" name="busqueda" placeholder="Buscar por usuario o correo" class="input-busqueda" value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
            <button type="submit" class="boton-lupa">&#128269;</button> <!-- lupa como ícono -->
        </div>
    </form>
</div>


    <?php if (isset($datos)) { ?>
    <form method="post" style="max-width:400px; margin:auto;">
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
    <hr style="margin:40px 0;">
    <?php } ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $usuarios->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila["id"]; ?></td>
            <td><?php echo $fila["usuario"]; ?></td>
            <td><?php echo $fila["correo"]; ?></td>
            <td><?php echo $fila["rol"]; ?></td>
            <td>
                <a href="editar_usuario.php?editar=<?php echo $fila["id"]; ?>">
                    <button>Editar</button>
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div style="text-align:center; margin-top:20px;">
        <a href="gestionar_usuarios.php">← Volver a gestión</a>
    </div>
</body>
</html>
