<?php

require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/../class/function.globales.php';
require_once __DIR__ . '/../class/class.User.php';

// Comprobamos que se ha accedido a esta api mediante POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    exit;
}

// Comprobamos que existe el campo tarea
$tarea = $_POST['tarea'] ?? null;
if (is_null($tarea)) {
    header('Content-Type: application/json');
    echo json_encode(['exito' => 0, 'mensaje' => 'No se ha recibido ninguna tarea']);
    exit;
}


$respuesta = array();
switch($tarea) {
    case 'VALIDATE_LOGIN':
        // Obtenemos los datos enviados
        $user = (isset($_POST['user']) && trim($_POST['user']) !== '') ? $_POST['user'] : null;
        $password = (isset($_POST['password']) && trim($_POST['password']) !== '') ? $_POST['password'] : null;

        if (!is_null($user) && !is_null($password)) {
            if (!validarEmail($user)) {
                $respuesta['exito'] = 0;
                $respuesta['mensaje'] = 'user/Contraseña incorrectos. VALIDACIÓN EMAIL MAL';

                break;
            }

            $user = new User(0, 'email', $user);
            
            if ($user->getidUser() == 0) {
                $respuesta['exito'] = 0;
                $respuesta['mensaje'] = 'user/Contraseña incorrectos. ID user MAL';

                break;
            }

            if ($user->getBlocked()) {
                $respuesta['exito'] = 0;
                $respuesta['mensaje'] = 'user bloqueado';
                break;
            }

            if ($user->checkPassword($password)) {
                $user->setFailedAttempts(0);
                $user->setDateHourLastAccess(date('Y-m-d H:i:s'));
                $user->setIpLastAccess(getUserIp());
                $user->save();

                // Crear la sesión
                require_once __DIR__ . '/../class/class.SecureSessionHandler.php'; 
                $sesion = new SecureSessionHandler();
                $sesion->start();
                $sesion->write('id', $user->getidUser());
                $sesion->write('ip', getUserIp());

                $respuesta['exito'] = 1;
                $respuesta['mensaje'] = '¡Te damos la bienvenida!';

            } else {
                $respuesta['exito'] = 0;
                $respuesta['mensaje'] = 'user/Contraseña incorrectos';

                $user->setFailedAttempts($user->getFailedAttempts() + 1);
                if ($user->getFailedAttempts() >= CONFIG_GENERAL['MAX_FAILED_ATTEMPS']) {
                    $user->setBlocked(true);
                    $respuesta['mensaje'] = 'Ha superado el máximo número de intentos fallidos. Su user ha sido bloqueado.';
                }
                $user->save();
            }
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Debe rellenar todos los campos';
        }

        break;

    default:
        $respuesta['exito'] = 0;
        $respuesta['mensaje'] = 'Error en la petición';
        break;
}

ob_clean();
header('Content-Type: application/json');
echo json_encode($respuesta);
