<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si usas otro usuario
$password = ""; // Cambia esto si tienes una contraseña
$dbname = "recogeloyadb"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>