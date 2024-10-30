<?php
include '../conexion.php';  // Asegúrate de que la conexión esté configurada correctamente

// Obtener los datos enviados por POST
$username = $_POST['username'];
$imagen_url = $_POST['imagen_url'];

// Validar que se reciban los datos necesarios
if (!empty($username) && !empty($imagen_url)) {
    // Preparar la consulta SQL para actualizar la columna 'perfil' con la URL de la imagen
    $sql = "UPDATE usuarios SET perfil = '$imagen_url' WHERE username = '$username'";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Devolver una respuesta exitosa en formato JSON
        echo json_encode(['status' => 'success', 'message' => 'Perfil actualizado con éxito']);
    } else {
        // Devolver un mensaje de error en caso de fallo en la actualización
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el perfil: ' . $conn->error]);
    }
} else {
    // Devolver un mensaje de error si faltan datos
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos necesarios']);
}

// Cerrar la conexión
$conn->close();
?>