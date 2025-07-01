<?php
include("db.php");

// Función que verifica si ya existe el usuario o correo
function existeUsuario($pdo, $usuario, $correo) {
    $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ? OR correo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario, $correo]);
    return $stmt->fetchColumn() > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id_usuarios"] ?? null; // ID del usuario, si se está editando
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $fechaNac = $_POST["fecha_nacimiento"];
    $rol    = $_POST["rol"];

    // Verificamos si ya existe el usuario o correo
    if (existeUsuario($pdo, $usuario, $correo)) {
        $mensaje = "El usuario o correo ya está registrado.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (id_usuarios, usuario, correo, clave, fecha_nacimiento, rol) VALUES (?,?,?, ?, ?, ?)");
            $stmt->execute([$id, $usuario, $correo, $clave,$fechaNac, $rol]);
            $mensaje = "Usuario agregado exitosamente.";
        } catch (PDOException $e) {
            $mensaje = "Error al registrar: " . $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Agregar Usuario</h2>
    <form method="post">
        <label for="id_usuarios">ID de Usuario:</label>
        <input type="text" name="id_usuarios" id="id_usuarios" placeholder="ID (Ej: A000001, B001)" required pattern="^[A,B]\d{4,6}$" title="Debe comenzar con 'A' seguido de 6 dígitos."><br>
        <input type="text" name="usuario" placeholder="Nombre" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <input type="password" name="clave2" placeholder="Repita la contraseña" required>
        <?php
        // Verificar si las contraseñas coinciden
        if (isset($_POST['clave']) && isset($_POST['clave2']) && $_POST['clave'] !== $_POST['clave2']) {
            echo "<p style='color:red;'>Las contraseñas no coinciden.</p>";
        }
        ?>
        <label for="fechaNac">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fechaNac" required><br>
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="Administrador">Administrador</option>
            <option value="Lector">Lector</option>
            <option value="Bibliotecario">Bibliotecario</option>
        </select><br>

        <input type="submit" value="Agregar Usuario">
    </form>

    <div class="botones">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='gestionar_usuarios.php'">
    </div>

    <?php if (isset($mensaje)) echo "<p style='text-align:center;'>$mensaje</p>"; ?>
</body>
</html>
