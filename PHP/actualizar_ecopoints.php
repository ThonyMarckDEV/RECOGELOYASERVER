<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// actualizar_ecopoints.php
header('Content-Type: application/json');

// Conectar a la base de datos
include '../conexion.php'; // Asegúrate de que este archivo contiene los detalles correctos para conectar con tu base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? 'defaultUsername';  // Añade un valor predeterminado para diagnosticar

    // Registra el valor de username recibido
    error_log("Received username for EcoPoints update: " . $username);

    // Consulta para aumentar los EcoPoints del usuario
    $query = "UPDATE usuarios SET ecoPoints = ecoPoints + 5 WHERE username = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array("status" => "success", "message" => "EcoPoints actualizados correctamente."));
        } else {
            echo json_encode(array("status" => "error", "message" => "No se pudo actualizar los EcoPoints. Asegúrese de que el usuario existe."));
        }
        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Error en la preparación de la consulta."));
    }

    $conn->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Solicitud incorrecta."));
}
?>
