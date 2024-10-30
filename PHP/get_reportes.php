<?php
// Incluir la conexión a la base de datos
include '../conexion.php';

// Consultar los reportes aprobados (estado 'revisado') y unir con la tabla de usuarios para obtener el username
$query = "SELECT reportes.idReporte, reportes.descripcion, reportes.imagen_url, reportes.latitud, reportes.longitud, usuarios.username 
          FROM reportes 
          JOIN usuarios ON reportes.idUsuario = usuarios.idUsuario 
          WHERE reportes.estado = 'revisado'";
$result = $conn->query($query);

$reportes = array();

if ($result->num_rows > 0) {
    // Almacenar los reportes en un array
    while ($row = $result->fetch_assoc()) {
        $reportes[] = $row;
    }
}

// Devolver los reportes como JSON
header('Content-Type: application/json');
echo json_encode($reportes);

// Cerrar la conexión
$conn->close();
?>
