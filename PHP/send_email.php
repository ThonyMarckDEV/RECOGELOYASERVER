<?php
// Incluye el archivo de configuración de PHPMailer
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener la hora y fecha actual
$hora_actual = date("H:i:s");
$fecha_actual = date("Y-m-d");

// Incluir la conexión a la base de datos
include '../conexion.php'; // Asegúrate de que este archivo esté configurado correctamente

// Recuperar el nombre de usuario desde la solicitud POST
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Preparar la consulta SQL
    $stmt = $conn->prepare("SELECT correo FROM usuarios WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($correo_destino);
    $stmt->fetch();
    $stmt->close();
 
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ecoalerta24@gmail.com';  // Correo desde el cual enviarás el mensaje
            $mail->Password = 'oajb yuqc cwha bzdd';  // Clave de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('ecoalerta24@gmail.com', 'ECOALERTA');
            $mail->addAddress($correo_destino);  // Dirección del destinatario

            // Adjuntar la imagen
            $mail->addEmbeddedImage('../img/6289.jpg', 'alerta'); // Reemplaza con la ruta de tu imagen

            // Contenido del correo
            $mail->isHTML(true);  // Establecer formato HTML
            $mail->Subject = 'EL BASURERO ESTA POR TU ZONA!!!';
            $mail->Body = '<p><img src="cid:alerta" alt="Decoración" style="display: block; margin: 0 auto;"/></p>'
                        . '<p>El camion de basura esta por tu zona, SACA LA BASURA!!!!:</p>'
                        . "Fecha: $fecha_actual<br>"
                        . "Hora: $hora_actual<br>";
            $mail->AltBody = "El camión pasó por tu zona!!!:\nFecha: $fecha_actual\nHora: $hora_actual";

            // Enviar el correo
            $mail->send();
            echo "Correo enviado correctamente a $correo_destino.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "No se encontró ningún correo en la base de datos.";
    }
    $conn->close();
?>