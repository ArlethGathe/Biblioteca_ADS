<?php
session_start();
include("db.php");


$id = $_SESSION['id_usuarios']; // asumimos que ya guardaste el ID en sesión al hacer login

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuarios = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['actualizar'])) {
        $nuevoUsuario = $_POST['usuario'];
        $nuevoCorreo = $_POST['correo'];
        $stmt = $pdo->prepare("UPDATE usuarios SET usuario = :usuario, correo = :correo WHERE id_usuarios = :id");
        $stmt->execute([
            ':usuario' => $nuevoUsuario,
            ':correo' => $nuevoCorreo,
            ':id' => $id
        ]);
        $mensaje = "✅ Perfil actualizado correctamente.";
        $usuario['usuario'] = $nuevoUsuario;
        $usuario['correo'] = $nuevoCorreo;
    }

    if (isset($_POST['cambiar_clave'])) {
        $nuevaClave = $_POST['nueva_clave'];
        $stmt = $pdo->prepare("UPDATE usuarios SET clave = :clave WHERE id_usuarios = :id");
        $stmt->execute([
            ':clave' => $nuevaClave,
            ':id' => $id
        ]);
        $mensaje = "🔐 Contraseña actualizada.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Mi Perfil</h2>

    <?php if (isset($mensaje)) echo "<p style='color: green;'>$mensaje</p>"; ?>

    <form method="post">
        <label>ID de usuario:</label>
        <input type="text" value="<?php echo $usuario['id_usuarios']; ?>" readonly><br>

        <label>Nombre de usuario:</label>
        <input type="text" name="usuario" value="<?php echo $usuario['usuario']; ?>" required><br>

        <label>Correo:</label>
        <input type="email" name="correo" value="<?php echo $usuario['correo']; ?>" required><br>

        <input type="submit" name="actualizar" value="Actualizar datos">
    </form>

    <h2>Cambiar contraseña</h2>
    <form method="post">
        <label>Nueva contraseña:</label>
        <input type="password" name="nueva_clave" required><br>
        <label>Repita su nueva contraseña:</label>
        <input type="password" name="nueva_clave2" required><br>
        <?php
        // Verificar si las contraseñas coinciden
        if (isset($_POST['nueva_clave']) && isset($_POST['nueva _clave2']) && $_POST['nueva_clave'] !== $_POST['nueva_clave2']) {
            echo "<p style='color:red;'>Las contraseñas no coinciden.</p>";
        }  
        ?> 
        <input type="submit" name="cambiar_clave" value="Cambiar contraseña">
        
    </form>
    <input type="button" value="Volver al Catálogo" class="btn-back" onclick="window.location.href='home_usuario.php'">
    
</body>
</html>
