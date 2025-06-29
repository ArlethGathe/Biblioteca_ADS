<?php
session_start();
include("db.php"); // Asegúrate de que la conexión PDO está configurada correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Usando PDO para la consulta preparada
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave";
    $stmt = $pdo->prepare($sql);  // Prepara la consulta
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':clave', $clave);
    $stmt->execute();

    // Verifica si se encontró el usuario
    if ($stmt->rowCount() == 1) {
        // Almacenar el usuario y su id en la sesión
        $usuario_data = $stmt->fetch();
        $_SESSION["usuario"] = $usuario_data['usuario'];
        $_SESSION["id"] = $usuario_data['id'];  // Asegúrate de almacenar el ID aquí
        $_SESSION["rol"] = $usuario_data['rol']; // Almacenar también el rol

        header("Location: home_usuario.php"); // Redirige si las credenciales son correctas
    } else {
        $error = "Credenciales inválidas"; // Si no hay coincidencias, muestra el error
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="Sinicio">
    <div class="iniS">
    <h2>Iniciar Sesión</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <input type="submit" value="Entrar" >
        <input type="button" value="¿No tienes cuenta? Regístrate" onclick="window.location.href='register.php'">
        
        <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
    </form>
</div>
</body>
</html>
