<?php
header('Content-Type: application/json');

// Incluir archivo de conexión a la base de datos
include '../conexion.php';

// Consulta SQL para obtener los usuarios ordenados por ecoPoints de mayor a menor
$sql = "SELECT username, perfil, ecoPoints FROM usuarios WHERE rol = 'usuario' AND ecoPoints >=1 ORDER BY ecoPoints DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuarios = array();
    while($row = $result->fetch_assoc()) {
        array_push($usuarios, array(
            "username" => $row["username"],
            "perfil" => $row["perfil"],
            "ecoPoints" => $row["ecoPoints"]
        ));
    }
    echo json_encode(array("status" => "success", "usuarios" => $usuarios));
} else {
    echo json_encode(array("status" => "error", "message" => "No se encontraron usuarios"));
}

$conn->close();
?>
