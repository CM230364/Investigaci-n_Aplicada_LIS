<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];  // Cambiado de 'usuario' a 'correo'
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE correo = ?"); // Cambiado de 'usuarios' a 'usuario' y de 'usuario' a 'correo'
    $stmt->bind_param("s", $correo);  // Cambiado de 'usuario' a 'correo'
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($contrasena, $row['contrasena'])) {
            $_SESSION['usuario_id'] = $row['id']; // Guardamos el ID del usuario en la sesión
            $_SESSION['usuario_nombre'] = $row['nombre']; // Guardamos el nombre del usuario
            $_SESSION['inicio'] = time();
            setcookie("usuario_id", $row['id'], time() + (86400 * 5), "/"); // Guardamos el ID en una cookie
            header("Location: indexcrud.php");
            exit();
        }
    }

    echo "Correo o contraseña incorrectos."; // Cambiado el mensaje para reflejar el uso de correo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form method="POST">
        <input type="text" name="correo" placeholder="Correo" required><br>  <input type="password" name="contrasena" placeholder="Contraseña" required><br>
        <button type="submit" class="btn">Ingresar</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </form>

  
    <div class="register-link">
        <p>¿No tienes cuenta? <a href="registro.php" class="btn-register">Regístrate aquí</a></p>
    </div>
</div>

</body>
</html>