<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
include '../conexion.php';

// Obtener las credenciales del POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Verificar las credenciales del usuario
$sql = "SELECT idUsuario, password, rol, ecoPoints FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $user = $result->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($password, $user['password'])) {
        // Actualizar el estado del usuario a 'loggedOn'
        $update_sql = "UPDATE usuarios SET status = 'loggedOn' WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $username);
        $update_stmt->execute();

        // Enviar el idUsuario, ecoPoints, rol y nombre de usuario como respuesta
        echo json_encode(array(
            "status" => "success",
            "idUsuario" => $user['idUsuario'],
            "ecoPoints" => $user['ecoPoints'],
            "rol" => $user['rol'],
            "username" => $username
        ));
    } else {
        // Enviar error si la contraseña es incorrecta
        echo json_encode(array("status" => "error", "message" => "Contraseña incorrecta"));
    }
} else {
    // Enviar error si el usuario no existe
    echo json_encode(array("status" => "error", "message" => "Usuario no encontrado"));
}

// Cerrar la conexión
$conn->close();
?>
