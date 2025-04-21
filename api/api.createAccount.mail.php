<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/../class/function.globales.php';
require_once __DIR__ . '/../class/class.User.php';
require_once __DIR__ . '/../welcome.mail/sendWelcomeEmail.php';

/* @var Usuario $actualUser */
global $actualUser;

// Asegurar que el método es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Obtener tarea
$tarea = $_POST['tarea'] ?? null;
if (is_null($tarea)) {
    header('Content-Type: application/json');
    echo json_encode(['exito' => 0, 'mensaje' => 'No se ha recibido ninguna tarea']);
    exit;
}

$respuesta = [];
switch ($tarea) {
    case 'CREATE_ACCOUNT':
        $newUser = new User();

        $name  = sanitizarString(trim($_POST['name'] ?? ''));
        $email = sanitizarString(trim($_POST['email'] ?? ''));
        $pass1 = sanitizarString(trim($_POST['password'] ?? ''));
        $pass2 = sanitizarString(trim($_POST['repeatedPassword'] ?? ''));

        // Validaciones básicas de nombre y email
        if ($name === '') {
            $respuesta = ['exito' => 0, 'errorName' => 1, 'mensaje' => 'Debes rellenar tu nombre'];
            break;
        }
        if ($email === '') {
            $respuesta = ['exito' => 0, 'errorMail' => 1, 'mensaje' => 'Debes rellenar tu mail'];
            break;
        }

        // Comprobación de duplicados por email
        $handler = new HandlerDB();
        $exists = $handler->getRecords(
            TABLE_USERS,
            ['idUser'],
            'email = :email',
            [':email' => $email],
            null,
            'FETCH_ASSOC',
            1
        );
        
        if (!empty($exists)) {
            // Ya existe una cuenta con este email
            header('Content-Type: application/json');
            echo json_encode(['exito' => 0, 'errorMail' => 1, 'mensaje' => 'Ya existe esta cuenta registrada']);
            exit;
        }

        // Validación de contraseñas
        if ($pass1 === '') {
            $respuesta = ['exito' => 0, 'mensaje' => 'Debes rellenar el campo contraseña'];
            break;
        }
        if ($pass2 === '') {
            $respuesta = ['exito' => 0, 'errorRepeatedPassword' => 1, 'mensaje' => 'Debes repetir la contraseña'];
            break;
        }
        if ($pass1 !== $pass2) {
            $respuesta = ['exito' => 0, 'mensaje' => 'Las contraseñas no coinciden'];
            break;
        }

        // Requisitos de la contraseña
        if (!checkPasswordRequirements($pass1, $pass2)) {
            $respuesta = ['exito' => 0, 'mensaje' => 'La contraseña no cumple los requisitos'];
            break;
        }

        // Almacenar nuevo usuario
        $newUser->setName($name);
        $newUser->setEmail($email);
        $newUser->setPassword($pass1);
        $newUser->setIdRol(3);
        $newUser->save();

        // Enviar email de bienvenida
        sendWelcomeEmail($email, $name);

        // Crear sesión
        require_once __DIR__ . '/../class/class.SecureSessionHandler.php';
        $sesion = new SecureSessionHandler();
        $sesion->start();
        $sesion->write('id', $newUser->getIdUser());
        $sesion->write('ip', getUserIp());

        $respuesta = ['exito' => 1, 'mensaje' => '¡Éxito en la creación de la nueva cuenta! ¡Te damos la bienvenida! Cargando...'];
        break;

    default:
        $respuesta = ['exito' => 0, 'mensaje' => 'Error en la petición de guardar al nuevo usuario'];
        break;
}

header('Content-Type: application/json');
echo json_encode($respuesta);
?>


