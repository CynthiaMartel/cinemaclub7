<?php
//PARA FUTURO//--
// PROBLEMA: Al tener  configurado el OAuth consent screen en modo testing, expiera sí o sí en 7 días. Tendría que hacerlo en modo producción, pero se me exige un dominio que no tengo
//Este archivo utiliza PHPMailer y el access_token obtenido mediante la función getNewAccessToken() para autenticar y enviar un correo
// El correo se envía una vez el usuario cree una nueva cuenta (*en api.createAccount.mail.php se llama a la función sendWelcomeEmail()*)
//La función sendWelcomeEmail se crea en este archivo para obtener el token y luego se usa para configurar la autenticación SMTP de PHPMailer.
// Además, se envía un email de confirmación a mi correo "codingdreamroutes.cynthiamartel@gmail.com" para verificar el envío de este correo de bienvenida

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'getAccessToken.php';  

// Credenciales de Google
$clientId = '354391895094-j9s20jbqrj4ss539f2uj02t69g5d01v2.apps.googleusercontent.com';
$clientSecret = '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';
$refreshToken = '1//03xjbpPqdFHhLCgYIARAAGAMSNwF-L9Irg7WaN1QnHmpjdCz-egISWnfgxYGNMCSrIrpkgngYteJK9dOgvpYTM1rHH4Z4VQB7RlU';
$emailFrom = 'codingdreamroutes.cynthiamartel@gmail.com';

function sendWelcomeEmail($toEmail, $toName) {
    global $clientId, $clientSecret, $refreshToken, $emailFrom;

    $provider = new Google([
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
    ]);

    $mail = new PHPMailer(true);

    try {
        // Obtener el nuevo access token
        $accessToken = getNewAccessToken($refreshToken);

        if ($accessToken === false) {
            throw new Exception('Error al obtener el access token.');
        }

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';

        // Configurar el token de acceso
        $mail->setOAuth(new \PHPMailer\PHPMailer\OAuth([
            'provider' => $provider,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'accessToken' => $accessToken,  // Usar el token renovado
            'refreshToken' => $refreshToken,
            'userName' => $emailFrom,
        ]));

        // Configuración del correo
        $mail->setFrom($emailFrom, 'CinemaClub7');
        $mail->addAddress($toEmail, $toName);

        $mail->Subject = 'Te damos la beinvenida a CinemaClub7!';
        $mail->Body = "Hola $toName,\n\nGracias por registrarte en CinemaClub. ¡Nos alegra mucho de que formes parte de nuestra comunidad cinéfila!🎬🍿\n\nEsperamos que disfrutes de tu experiencia y puedas generar redes con otros amantes del cine\n\n🎬 Recuerda: Watch, Rate, Debate.\n\nExplora películas, puntúa, y únete a los debates del CineClub7, en donde tu participación será clave para hacernos crecer.\n\n🎥 ¡Nos vemos en las pantallas! \n\n— El equipo de CinemaClub7";

        // Enviar el correo de bienvenida
        $mail->send();

        // Enviar correo de verificación a mí misma
        $mail->clearAddresses();  // Limpiar las direcciones previas
        $mail->addAddress('codingdreamroutes.cynthiamartel@gmail.com');  // Tu correo de verificación
        $mail->Subject = 'Enviado correo de bienvenida a nuevo usuario: CinemaClub';
        $mail->Body = "Se ha enviado un correo de bienvenida a: $toEmail ($toName).";

        // Enviar el correo de verificación
        $mail->send();

        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}


