<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $existe = $stmt->fetchColumn();

    if ($existe) {
        $error = "El nombre de usuario ya está registrado. Elige otro.";
    } else {
        // Registrar el nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, correo, clave) VALUES ('$usuario', '$correo', '$clave')");
        if ($stmt->execute([$usuario, $correo, $clave])) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error al registrar.";
        }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="Sinicio">
    <div class="iniS">
       <h2>Registro de Usuario</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <input type="submit" value="Registrarse">
        <input type="button" value="Volver" onclick="window.location.href='index.php'">

        
        <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    </form>
        </div>
</div>
</body>
</html>