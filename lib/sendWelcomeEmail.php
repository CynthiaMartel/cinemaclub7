<?php
// El archivo .env debería crearse con git.ignore para no subir las credenciales al repositorio, 
// pero como es un trabajo de clase y una prueba, lo hacemos sin ese git.ignore
// sendWelcomeEmail.php

// Usando PHPMailer con App Password de Gmail y variables de entorno

// sendWelcomeEmail.php
// Usando PHPMailer con App Password de Gmail y variables de entorno
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

// Carga de variables de entorno (busca el .env en esta misma carpeta)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Ahora mis credenciales quedan en estas variables que están en el archivo lib/.env (deberíamos usar un git.ignore para privacidad):
$emailFrom     = $_ENV['MAIL_USERNAME'];
$appPassword   = $_ENV['MAIL_APP_PASSWORD'];
$emailVerified = $emailFrom; // confirma el envío a ti mismo

/**
 * Envía el correo de bienvenida y luego un correo de notificación para mi cuenta.
 *
 * @param string $toEmail Email del usuario nuevo
 * @param string $toName  Nombre del usuario nuevo
 * @return bool           True si todo OK, false si hay error
 */
function sendWelcomeEmail($toEmail, $toName) {
    global $emailFrom, $appPassword, $emailVerified;

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP con App Password
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth   = true;
        $mail->Username   = $emailFrom;
        $mail->Password   = $appPassword;

        // Correo de bienvenida al usuario
        $mail->setFrom($emailFrom, 'CinemaClub7');
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = 'Te damos la bienvenida a CinemaClub7!';
        $mail->Body    = "Hola $toName,\n\n"
                       . "Gracias por registrarte en CinemaClub7. ¡Nos alegra mucho que formes parte "
                       . "de nuestra comunidad cinéfila! 🎬🍿\n\n"
                       . "Esperamos que disfrutes tu experiencia y conectes con otros amantes del cine.\n\n"
                       . "🎬 Recuerda: Watch, Rate, Debate.\n\n"
                       . "— El equipo de CinemaClub7";
        $mail->send();

        // Notificación para mi cuenta
        $mail->clearAddresses();
        $mail->addAddress($emailVerified);
        $mail->Subject = 'Enviado correo de bienvenida a nuevo usuario';
        $mail->Body    = "Se ha enviado un correo de bienvenida a: $toEmail ($toName).";
        $mail->send();

        return true;

    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}

