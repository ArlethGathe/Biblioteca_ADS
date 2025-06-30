<?php
include("db.php");

function generarIdUsuario($pdo) {
    do {
        $id = 'A' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE id_usuarios = :id");
        $stmt->execute([':id' => $id]);
        $existe = $stmt->fetchColumn() > 0;
    } while ($existe);
    return $id;
}

$id_usuarios = null;
$mensaje = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $fechaNac = $_POST["fechaNac"];
    $rol = "lector"; // Asignado por defecto

    $id_usuarios = generarIdUsuario($pdo);

    $sql = "INSERT INTO usuarios (id_usuarios, usuario, correo, clave, fecha_nacimiento, rol, creado_at)
            VALUES (:id, :usuario, :correo, :clave, :fecha_nacimiento, :rol, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuarios);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':clave', $clave); // sin cifrado
    $stmt->bindParam(':fecha_nacimiento', $fechaNac);
    $stmt->bindParam(':rol', $rol);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Registro exitoso. Tu ID asignado es: <strong>$id_usuarios</strong>";
    } else {
        $error = "❌ Error al registrar usuario.";
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
       <h2>Registro de Lector</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Nombre Completo" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <label for ="fechaNac">Fecha de Nacimiento: </label>
        <input type ="date" name="fechaNac" id="fechaNac"  required><br>

        <input type="submit" value="Registrarse">
        <input type="button" value="Volver" class="btn-back" onclick="window.location.href='index.php'">
      
    </form>

    <?php
    if (isset($mensaje)) {
        echo "<p style='color: green; text-align:center;'>$mensaje</p>";
    } elseif (isset($error)) {
        echo "<p style='color: red; text-align:center;'>$error</p>";
    }
    ?>
</body>
</html>