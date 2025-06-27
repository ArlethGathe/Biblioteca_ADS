<?php
include("db.php");

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $conexion->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: eliminar_usuario.php?msg=eliminado");
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
    <title>Eliminar Usuario</title>
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
        button {
            background-color: #cc0000;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>Eliminar Usuario</h2>

    <div class="contenedor-busqueda">
    <form method="GET" action="">
        <div class="input-con-boton">
            <input type="text" name="busqueda" placeholder="Buscar por usuario o correo" class="input-busqueda" value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
            <button type="submit" class="boton-lupa">&#128269;</button> <!-- lupa como ícono -->
        </div>
    </form>
</div>


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
                <a href="eliminar_usuario.php?eliminar=<?php echo $fila["id"]; ?>" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                    <button>Eliminar</button>
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
