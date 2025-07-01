<?php
include("db.php");

// Obtener todos los usuarios
$multas = $pdo->query("SELECT * FROM multas")->fetchALL(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multas</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="estilo_HomeUsuario.css">
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
    <div class="container">
        <h2>Multas</h2>
        
        <div class="acciones">
            <div class="mb-4">
                <a href="agregar_multa.php" class="btn-add">Agregar Multa</a>
            </div>
            <div class="mb-4">
                <a href="editar_multa.php" class="btn-add">Editar Multa</a>
            </div>
            <div class="mb-4">
                <a href="eliminar_multa.php" class="btn-add">Eliminar Multa</a>
            </div>
    </div>
    <table>
        <tr>
            <th># Multa</th>
            <th>Usuario</th>
            <th>Libro</th>
            <th>Fecha Vencida</th>
            <th>Cantidad</th>
            <th>Descripcion</th>
        </tr>

         <?php foreach ($multas as $fila) { ?>
        <tr>
            <td><?php echo $fila["id_multa"]; ?></td>
            <td><?php echo $fila["usuario"]; ?></td>
            <td><?php echo $fila["titulo"]; ?></td>
            <td><?php echo $fila["fecha_vencimiento"]; ?></td>
            <td><?php echo $fila["cantidad_pesos"]; ?></td>
            <td><?php echo $fila["descripcion"]; ?></td>
        </tr>
        <?php } ?> 
    </table>
    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='home_usuario.php'">
    </div>

        
</body>
</html>