<?php
include("db.php");

// Obtener todos los usuarios
$usuarios = $conexion->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
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
        tr:nth-child(even) {
            background-color: #e6f0ff;
        }
    </style>
</head>
<body>
    <h2>Listado de Todos los Usuarios</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Rol</th>
        </tr>

        <?php while ($fila = $usuarios->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila["id"]; ?></td>
            <td><?php echo $fila["usuario"]; ?></td>
            <td><?php echo $fila["correo"]; ?></td>
            <td><?php echo $fila["rol"]; ?></td>
        </tr>
        <?php } ?>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <a href="gestionar_usuarios.php">← Volver a gestión</a>
    </div>
</body>
</html>
