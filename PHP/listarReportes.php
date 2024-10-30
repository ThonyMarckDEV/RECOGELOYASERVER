<?php
// Incluir la conexión a la base de datos
include '../conexion.php';

try {
    // Consulta para obtener los reportes con estado "pendiente"
    $sql = "SELECT reportes.idReporte, reportes.idUsuario, reportes.fecha, reportes.descripcion, reportes.imagen_url, usuarios.username 
            FROM reportes 
            JOIN usuarios ON reportes.idUsuario = usuarios.idUsuario 
            WHERE reportes.estado = 'pendiente'";
    $result = $conn->query($sql);

    $reportes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reporte = array(
                'idReporte' => $row['idReporte'],
                'username' => $row['username'],  // Nombre de usuario
                'fecha' => $row['fecha'],
                'descripcion' => $row['descripcion'],
                'imagen_url' => $row['imagen_url']  // URL de la imagen
            );
            array_push($reportes, $reporte);
        }
    }

    // Devolver los reportes en formato JSON
    echo json_encode($reportes);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
