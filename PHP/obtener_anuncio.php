<?php
date_default_timezone_set('America/Lima');
// Incluir la conexión a la base de datos
include '../conexion.php';

$id_usuario = $_GET['id_usuario']; // Obtener el ID del usuario desde la solicitud

// Obtener la fecha actual en formato YYYY-MM-DD
$fecha_actual = date('Y-m-d'); // Corregido el formato de fecha

// Seleccionar un anuncio que no haya sido visto por este usuario
$sql = "SELECT a.id, a.titulo, a.mensaje 
            FROM anuncios a 
            LEFT JOIN anuncios_vistos av ON a.id = av.idAnuncio AND av.idUsuario = ? 
            WHERE av.idAnuncio IS NULL 
            AND DATE(a.fecha) = ? 
            ORDER BY a.fecha ASC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id_usuario, $fecha_actual);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $anuncio = $result->fetch_assoc();
    echo json_encode($anuncio);

    // Verificar que los valores de idUsuario y idAnuncio no sean nulos
    if (isset($id_usuario) && isset($anuncio['id'])) {
        // Registrar que este usuario ha visto el anuncio
        $insertSql = "INSERT INTO anuncios_vistos (idUsuario, idAnuncio) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($insertSql);

        if (!$stmt_insert) {
            // Mostrar el error de preparación de la consulta si falla
            echo json_encode(["status" => "error", "message" => "Error en la preparación de la consulta: " . $conn->error]);
            exit;
        }

        $stmt_insert->bind_param("ii", $id_usuario, $anuncio['id']);
        
        if ($stmt_insert->execute()) {
            echo json_encode(["status" => "success", "message" => "Anuncio registrado en anuncios_vistos"]);
        } else {
            // Mostrar el error de la ejecución de la consulta si falla
            echo json_encode(["status" => "error", "message" => "Error al insertar en anuncios_vistos: " . $stmt_insert->error]);
        }
        
        $stmt_insert->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Datos inválidos para idUsuario o idAnuncio"]);
    }

} else {
    echo json_encode(["status" => "no_data", "message" => "No hay anuncios disponibles"]);
}

$stmt->close();
$conn->close();
?>
