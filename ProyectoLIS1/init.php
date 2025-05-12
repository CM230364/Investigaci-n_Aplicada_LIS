<?php
session_start();

// Expira después de 10 minutos
if (isset($_SESSION['inicio']) && (time() - $_SESSION['inicio'] > 600)) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
$_SESSION['inicio'] = time();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
?>
