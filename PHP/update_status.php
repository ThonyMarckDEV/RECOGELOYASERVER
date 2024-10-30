<?php
// Incluir el archivo de conexión
include '../conexion.php';

// Obtener el nombre de usuario del request
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Preparar la consulta SQL para actualizar el estado del usuario
    $sql = "UPDATE usuarios SET status='loggedOff' WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    // Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        echo "Estado actualizado correctamente";
    } else {
        echo "Error al actualizar el estado: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
} else {
    echo "Nombre de usuario no proporcionado";
}

$conn->close();
?>
