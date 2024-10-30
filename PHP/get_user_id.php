<?php
// Conectar a la base de datos
include '../conexion.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Consulta para obtener el idUsuario según el username
    $query = "SELECT idUsuario FROM usuarios WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['idUsuario']; // Enviar el idUsuario de vuelta
    } else {
        echo "0"; // Si no se encuentra el usuario
    }
}
?>
