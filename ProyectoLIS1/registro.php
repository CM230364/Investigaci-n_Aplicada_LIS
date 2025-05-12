<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre']; // Añadido el campo 'nombre'
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuario (nombre, correo, contrasena) VALUES (?, ?, ?)"); // Cambiado de 'usuarios' a 'usuario'
    $stmt->bind_param("sss", $nombre, $correo, $contrasena);  // Añadido 's' para el campo 'nombre'
    $stmt->execute();

    // Mensaje para indicar que el usuario fue registrado correctamente
    echo "<p class='success'>Usuario registrado correctamente.</p>";

    // Redirigir con JavaScript después de 2 segundos
    echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000); // 2 segundos de espera antes de redirigir
          </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>

<div class="container">
    <div class="form-box">
        <h2>Crear Cuenta</h2>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="nombre" required>
                <label>Nombre completo</label>
            </div>
            <div class="input-group">
                <input type="text" name="correo" required>
                <label>Correo electrónico</label>
            </div>
            <div class="input-group">
                <input type="password" name="contrasena" required>
                <label>Contraseña</label>
            </div>
            <button type="submit" class="btn">Registrarse</button>
        </form>
    </div>
</div>

</body>
</html>
