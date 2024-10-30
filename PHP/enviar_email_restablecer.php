<?php
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['correo']) && isset($_POST['codigo'])) {
    $correo_destino = $_POST['correo'];
    $codigo = $_POST['codigo'];

    // Crear una instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ecoalerta24@gmail.com';
        $mail->Password = 'oajb yuqc cwha bzdd'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('LimpialoYA@gmail.com', 'LimpialoYA');
        $mail->addAddress($correo_destino);  // Dirección del destinatario

        // Contenido del correo
        $mail->isHTML(true);  
        $mail->Subject = 'Tu código de verificación';
        $mail->Body = "Tu código de verificación es: $codigo";

        // Enviar el correo
        $mail->send();
        echo "Correo enviado correctamente.";
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
} else {
    echo "Faltan parámetros en la solicitud.";
}
?>