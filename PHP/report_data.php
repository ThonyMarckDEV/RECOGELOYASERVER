<?php
// Incluir la conexión a la base de datos
include '../conexion.php';

// Inicializar la respuesta
$response = 'error';

// Verificar que todos los parámetros necesarios estén presentes
if (isset($_POST['idUsuario']) && isset($_POST['fecha']) && isset($_POST['descripcion']) && isset($_POST['imagen_url']) && isset($_POST['latitud']) && isset($_POST['longitud'])) {
    $idUsuario = $_POST['idUsuario'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $imagenUrl = $_POST['imagen_url'];  // URL de la imagen que ya fue subida al servidor
    $latitud = $_POST['latitud'];  // Latitud enviada desde la app
    $longitud = $_POST['longitud'];  // Longitud enviada desde la app
    $estado = 'pendiente';  // El estado predeterminado es "pendiente"

    // Verificar si ya existe un reporte pendiente para este idUsuario
    $checkStmt = $conn->prepare("SELECT idReporte FROM reportes WHERE idUsuario = ? AND estado = 'pendiente'");
    $checkStmt->bind_param("i", $idUsuario);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Si hay al menos un reporte pendiente, devolver un mensaje y no permitir subir un nuevo reporte
        $response = 'Tiene un reporte pendiente de revisión, espera que lo acepten para subir otro.';
    } else {
        // No hay reporte pendiente, proceder a insertar el nuevo reporte
        $stmt = $conn->prepare("INSERT INTO reportes (idUsuario, fecha, descripcion, imagen_url, latitud, longitud, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdds", $idUsuario, $fecha, $descripcion, $imagenUrl, $latitud, $longitud, $estado); // "s" para el estado

        if ($stmt->execute()) {
            $response = 'success';
        } else {
            $response = 'Error al guardar el reporte: ' . $stmt->error;
        }
        $stmt->close();
    }
    $checkStmt->close();
} else {
    $response = 'Faltan parámetros';
}

echo $response;
$conn->close();
?>
