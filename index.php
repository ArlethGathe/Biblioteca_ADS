<?php
session_start();
include("db.php"); // Asegúrate de que $pdo esté definido correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegúrate de que los nombres coincidan con los del formulario HTML
    $id = $_POST["id_usuarios"];
    $clave = $_POST["clave"];

    // Consulta segura con parámetros bien definidos
    $sql = "SELECT * FROM usuarios WHERE id_usuarios = :id_usuarios AND clave = :clave";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuarios', $id);
    $stmt->bindParam(':clave', $clave);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $usuario_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION["usuario"] = $usuario_data['usuario'];
        $_SESSION["id_usuarios"] = $usuario_data['id_usuarios'];
        $_SESSION["rol"] = $usuario_data['rol'];

        header("Location: home_usuario.php");
        exit;
    } else {
        $error = "Credenciales inválidas";
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
    <header>
        <h1>Biblioteca Digital</h1>
    </header>
    <div class="iniS">
    <h2>Iniciar Sesión</h2>
    <div class="form-inicio">
        <form method="post">
            <input type="text"  name="id_usuarios" placeholder="ID" required>
            <input type="password" id="clave" name="clave" placeholder="Contraseña" required>
            <input type="checkbox" id="mostrarPass">
            <label class="mostrar">Mostrar Contraseña</label><br>
            <script src="mostrar_contraseña.js"></script>
            <input type="submit" value="Entrar" >
            <input type="button" value="¿No tienes cuenta? Regístrate" onclick="window.location.href='register.php'">
            
            <?php if (isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
        </form>
    </div>
</div>
</body>
</html>
