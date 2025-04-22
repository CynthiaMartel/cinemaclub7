<?php
//PARA FUTURO//--
// PROBLEMA: Al tener  configurado el OAuth consent screen en modo testing, expiera sí o sí en 7 días. Tendría que hacerlo en modo producción, pero se me exige un dominio que no tengo
//Creación de la función getNewAccessToken(), encargada de obtener un nuevo access_token usando el refresh_token proporcionado. (Conseguido con solicitud POST en Postman)

// Explicación: 
// 1- El access_token solo dura 7 días, pero el refresh_token puede ser utilizado para renovarlo.
// 2- La función realiza una solicitud POST al servidor de Google OAuth para obtener el nuevo token y lo devuelve.
//3- El access_token solo dura 7 días, pero el refresh_token puede ser utilizado para renovarlo.

// Con esta configuración, se podrá enviar correos continuamente sin perocuparnos de la expiración del token.



function getNewAccessToken($refreshToken) {
    $clientId = '354391895094-j9s20jbqrj4ss539f2uj02t69g5d01v2.apps.googleusercontent.com';
    $clientSecret = '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';
    $url = 'https://oauth2.googleapis.com/token';

    $data = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'refresh_token' => $refreshToken,
        'grant_type' => 'refresh_token',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $jsonResponse = json_decode($response, true);

    if (isset($jsonResponse['access_token'])) {
        return $jsonResponse['access_token'];
    } else {
        // Manejo de errores si no se obtiene el token
        return false;
    }
}

