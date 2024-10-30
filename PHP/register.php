<?php
// Incluir el archivo de conexión
include '../conexion.php';

// Obtener los datos del POST
$username = $_POST['username'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$password = $_POST['password']; // Esta es la contraseña en texto claro
$status = 'loggedOff'; // Valor por defecto
$role = 'usuario'; // Valor por defecto

// Verificar si el nombre de usuario o correo ya existen
$sql_check = "SELECT * FROM usuarios WHERE username = ? OR correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Si existe, devolver un error
    echo json_encode(['status' => 'error', 'message' => 'Username o correo ya existe']);
} else {
    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Preparar y ejecutar la consulta
    $sql = "INSERT INTO usuarios (username, rol, nombres, apellidos, correo, password, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $role, $nombres, $apellidos, $email, $hashed_password, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registro exitoso']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en el registro']);
    }

    // Cerrar la conexión
    $stmt->close();
}

$stmt_check->close();
$conn->close();
?>