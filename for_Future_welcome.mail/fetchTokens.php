<!-- /PARA FUTURO//--  EL QUE USAMOS AHORA ESTÁ EN LIB!!!
// PROBLEMA: Al tener  configurado el OAuth consent screen en modo testing, expiera sí o sí en 7 días. Tendría que hacerlo en modo producción, pero se me exige un dominio que no tengo

Este archivo se usa en cmd para obtener el código de refresh Token que google manda. 
Hay que apuntar a: C:\xampp\htdocs\cinemaclub7\welcome.mail y presionar enter 
Así se obtiene el refresh token que se pegará en el archivo sendWelcomeEmail.php -->

<?php
/* require __DIR__ . '/../vendor/autoload.php';   // <-- ruta corregida

use League\OAuth2\Client\Provider\Google;

// 1) Configura el provider
$provider = new Google([
  'clientId'     => '354391895094-f30n6sh1isp27vfemj3aafg02g41j1lc.apps.googleusercontent.com',
  'clientSecret' => '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',
  'redirectUri'  => 'urn:ietf:wg:oauth:2.0:oob'
]);

// 2) Pega el código que Google te dio
echo "4/1Ab_5qll_Hl9OOiMEVRCxSL0aQanQAZMG3FBNd7e9wBhV2UqpSJuVHJtXKV8\n> ";
$authorizationCode = trim(fgets(STDIN));

// 3) Intercambia por tokens
$accessToken = $provider->getAccessToken('authorization_code', [
  'code' => $authorizationCode
]);

// 4) Extrae y guarda el refresh token
file_put_contents('.env', "GOOGLE_REFRESH_TOKEN={$accessToken->getRefreshToken()}\n", FILE_APPEND);

echo "Refresh Token: ", $accessToken->getRefreshToken(), "\n";
echo "Access Token:  ", $accessToken->getToken(), "\n";
echo "Expira en:    ", $accessToken->getExpires(), "\n"; */