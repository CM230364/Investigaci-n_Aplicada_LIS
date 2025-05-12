<?php
$servername = "db"; 
$username = "root";
$password = "";
$dbname = "citas_dentales";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>