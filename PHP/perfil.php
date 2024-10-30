<?php
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
include '../conexion.php';

// Obtener el username del POST
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("SELECT nombres, apellidos, correo FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Usuario no encontrado"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "No se proporcionó el username"]);
}

$conn->close();
?>