<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
include '../conexion.php';

// Obtener el nombre de usuario del POST request
$username = $_POST['username'];

// Preparar la consulta SQL para obtener los ecoPoints
$sql = "SELECT ecoPoints FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el usuario
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $ecoPoints = $user['ecoPoints'];
    echo json_encode(array("status" => "success", "ecoPoints" => $ecoPoints));
} else {
    // Enviar error si el usuario no existe
    echo json_encode(array("status" => "error", "message" => "Usuario no encontrado"));
}

// Cerrar la conexión
$conn->close();
?>
