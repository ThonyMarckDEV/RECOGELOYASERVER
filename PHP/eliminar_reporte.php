<?php
// Incluir la conexión a la base de datos
include '../conexion.php';

if (isset($_POST['idReporte'])) {
    $idReporte = $_POST['idReporte'];

    // Preparar la consulta para eliminar el reporte
    $stmt = $conn->prepare("DELETE FROM reportes WHERE idReporte = ?");
    $stmt->bind_param("i", $idReporte);

    if ($stmt->execute()) {
        echo "Reporte eliminado con éxito";
    } else {
        echo "Error al eliminar el reporte: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID de reporte no recibido";
}

$conn->close();
?>
