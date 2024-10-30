<?php
// Configura el directorio donde se almacenarán las imágenes
$upload_dir = 'uploads/';

// Incluir el archivo que contiene la clase UrlHelper
require_once 'UrlHelper.php';  // Asegúrate de poner la ruta correcta al archivo

// Crear una instancia de UrlHelper
$urlHelper = new UrlHelper();


// Verificar si la carpeta de destino existe, si no, crearla
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Verificar si se recibió un archivo
if ($_FILES['image']['error'] == 0) {
    // Obtener la información del archivo
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    
    // Generar un nombre único para el archivo (esto evitará que los archivos con el mismo nombre se sobrescriban)
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $new_file_name = uniqid('img_', true) . '.' . $file_ext;
    
    // Definir la ruta completa donde se almacenará la imagen
    $file_path = $upload_dir . $new_file_name;
    
    // Mover el archivo temporal al directorio de destino
    if (move_uploaded_file($file_tmp, $file_path)) {
        
        // Obtener la URL base desde UrlHelper
        $base_url = $urlHelper->getBaseUrl();
        
        // Concatenar la URL base con el path del archivo
        $image_url = $base_url . $file_path;
        
        // Devolver la URL de la imagen subida
        echo json_encode([
            'status' => 'success',
            'image_url' => $image_url
        ]);
    } else {
        // Error al mover el archivo
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al mover el archivo'
        ]);
    }
} else {
    // Error al recibir el archivo
    echo json_encode([
        'status' => 'error',
        'message' => 'No se ha recibido ninguna imagen o hubo un error en la subida'
    ]);
}
?>
