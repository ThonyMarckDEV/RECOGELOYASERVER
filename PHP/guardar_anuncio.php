<?php
// Incluir la conexión a la base de datos
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $mensaje = $_POST['mensaje'];

    if (!empty($titulo) && !empty($mensaje)) {
        // Inserción en la tabla 'anuncios'
        $sql = "INSERT INTO anuncios (titulo, mensaje, fecha) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $titulo, $mensaje);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Anuncio guardado con éxito"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al guardar el anuncio"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Campos vacíos"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}

$conn->close();
?>
