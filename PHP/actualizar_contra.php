<?php
// Incluye la conexión a la base de datos
include '../conexion.php'; // Asegúrate de que este archivo esté configurado correctamente

if (isset($_POST['correo']) && isset($_POST['nueva_contra'])) {
    $correo = $_POST['correo'];
    $nueva_contra = $_POST['nueva_contra'];

    // Hashear la nueva contraseña
    $nueva_contra_hashed = password_hash($nueva_contra, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la tabla 'usuarios'
    $stmt = $conn->prepare("UPDATE usuarios SET password=? WHERE correo=?");
    $stmt->bind_param("ss", $nueva_contra_hashed, $correo);

    if ($stmt->execute()) {
        echo "Contraseña actualizada correctamente.";
    } else {
        echo "Error al actualizar la contraseña.";
    }

    $stmt->close();
} else {
    echo "Faltan parámetros.";
}

$conn->close();
?>
