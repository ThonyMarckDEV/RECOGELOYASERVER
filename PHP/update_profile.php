<?php
    session_start();

    // Incluir la conexión a la base de datos
    include '../conexion.php'; // Asegúrate de que la ruta es correcta

    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Verificar si se reciben datos por POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener el username y los datos a actualizar
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $nombres = isset($_POST['nombres']) ? $_POST['nombres'] : null;
        $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : null;
        $correo = isset($_POST['correo']) ? $_POST['correo'] : null;

        // Verificar si el usuario existe
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(["status" => "error", "message" => "Usuario no encontrado."]);
            exit();
        }

        // Iniciar una transacción
        $conn->begin_transaction();

        try {
            $fields_to_update = [];
            $types = "";
            $values = [];

            // Verificar y agregar los campos modificados
            if ($nombres) {
                $fields_to_update[] = "nombres = ?";
                $types .= "s";
                $values[] = $nombres;
            }

            if ($apellidos) {
                $fields_to_update[] = "apellidos = ?";
                $types .= "s";
                $values[] = $apellidos;
            }

            $email_updated = false; // Declarar la variable por fuera
            if ($correo) {
                // Validar el correo electrónico
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Correo electrónico no válido.');
                }
                $fields_to_update[] = "correo = ?";
                $types .= "s";
                $values[] = $correo;
                $email_updated = true; // Marcar que se actualizó el correo
            }

            if ($email_updated) {
                enviarCorreoConfirmacion($correo);
            }


            // Solo realizar la actualización si hay campos modificados
            if (count($fields_to_update) > 0) {
                $sql_update = "UPDATE usuarios SET " . implode(", ", $fields_to_update) . " WHERE username = ?";
                $stmt_update = $conn->prepare($sql_update);
                if ($stmt_update === false) {
                    throw new Exception("Error en la preparación de la consulta: " . $conn->error);
                }

                // Agregar el username al final del array de valores
                $types .= "s";
                $values[] = $username;

                // Hacer bind de los parámetros
                $stmt_update->bind_param($types, ...$values);

                // Ejecutar la actualización
                $stmt_update->execute();
                $stmt_update->close();
            }

            $conn->commit();
            $conn->close();
            echo json_encode(["status" => "success", "message" => "Datos actualizados correctamente."]);
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $conn->close();
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    }

    function enviarCorreoConfirmacion($correo) {
        $mail = new PHPMailer(true);
    
        try {
            // Configuración del servidor de correo
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ecoalerta24@gmail.com';  // Correo desde el cual enviarás el mensaje
            $mail->Password = 'oajb yuqc cwha bzdd';  // Clave de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            // Configuración del correo
            $mail->setFrom('ecoalerta24@gmail.com', 'ECOALERTA'); // Cambia los datos del remitente
            $mail->addAddress($correo); // Correo del usuario
    
            // Agregar la imagen embebida
            $mail->addEmbeddedImage('../img/6289.jpg', 'alerta'); // Reemplaza con la ruta de tu imagen
    
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Actualizacion de informacion en ECOALERTA';
            $mail->Body = '
            <html>
                <body>
                    <p><img src="cid:alerta" alt="Decoración" style="display: block; margin: 0 auto; width: 100%; max-width: 600px;"/></p>
                    <h1>¡Hola!</h1>
                    <p>Tu información ha sido actualizada correctamente en la aplicación <strong>ECOALERTA</strong>.</p>
                    <p>Si no solicitaste este cambio, por favor contacta con nuestro soporte.</p>
                </body>
            </html>';
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $mail->ErrorInfo);
        }
    }
?>