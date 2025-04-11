<?php
/* require_once 'vendor/autoload.php';
require_once __DIR__ . '/../class/class.User.php';

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['credential'];

$client = new Google_Client(['client_id' => '354391895094-j9s20jbqrj4ss539f2uj02t69g5d01v2.apps.googleusercontent.com']);
$payload = $client->verifyIdToken($token);

if ($payload) {
    $email = $payload['email'];
    $name = $payload['name'];

    // Crear usuario si no existe
    $user = new User(0, 'email', $email);
    if (!$user->getidUser()) {
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(bin2hex(random_bytes(12)));
        $user->setDateHourLastAccess(date('Y-m-d H:i:s'));
        $user->setIpLastAccess($_SERVER['REMOTE_ADDR']);
        $user->setIdRol(3);

        if (!$user->save()) {
            echo json_encode(['success' => false, 'error' => 'No se pudo guardar el usuario']);
            exit;
        }
    }

    // Iniciar sesión
    session_start();
    $_SESSION['id'] = $user->getidUser();
    $_SESSION['name'] = $user->getName();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Token inválido']);
}  */
