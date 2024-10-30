<?php
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
include '../conexion.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Consultar la base de datos para obtener el estado
    $sql = "SELECT status FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Devolver el estado como JSON
    echo json_encode(['status' => $status]);
} else {
    echo json_encode(['error' => 'Username not provided']);
}

$conn->close();
?>
