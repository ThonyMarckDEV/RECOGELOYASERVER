<?php
// Incluir la conexión a la base de datos
include '../conexion.php';

// Verificar si se recibió el idReporte en la URL
if (isset($_GET['idReporte'])) {
    $idReporte = $_GET['idReporte'];

    // Preparar la consulta para actualizar el estado del reporte
    $sql = "UPDATE reportes SET estado = 'revisado' WHERE idReporte = ?";

    // Preparar la sentencia
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar el parámetro idReporte
        $stmt->bind_param('i', $idReporte);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "Reporte actualizado a revisado"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error al actualizar el reporte"));
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Error en la preparación de la consulta"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "idReporte no especificado"));
}

// Cerrar la conexión
$conn->close();
?>
