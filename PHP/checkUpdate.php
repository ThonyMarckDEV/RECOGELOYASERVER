<?php
// Conectar a la base de datos
include '../conexion.php';

// Obtener la última versión de la tabla updates
$sql = "SELECT version, link FROM updates ORDER BY idUpdate DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Retorna la versión y el link en formato CSV
    echo $row["version"] . "," . $row["link"];
} else {
    echo "error";
}

$conn->close();
?>