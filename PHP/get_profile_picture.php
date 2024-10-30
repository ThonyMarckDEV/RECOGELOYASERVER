<?php
header('Content-Type: application/json');

// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include '../conexion.php';

// Verificar si la conexión se ha establecido correctamente
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos']);
    exit;
}

try {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        // Preparar la consulta
        $stmt = $conn->prepare("SELECT perfil FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

       // Devolver solo el nombre del archivo, sin incluir la URL base
        if ($result) {
            $perfilUrl = $result['perfil'];
            if ($perfilUrl && !empty($perfilUrl)) {
                echo json_encode(['status' => 'success', 'perfil' => $perfilUrl]); // Solo el nombre del archivo
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Imagen de perfil no disponible']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Username no proporcionado']);
    }
} catch (Exception $e) {
    error_log('Error en el servidor: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error en el servidor', 'error' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
