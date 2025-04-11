<?php

require_once __DIR__ . '/../config/config.globales.php';
require_once __DIR__ . '/comprobar.sesion.php';
require_once __DIR__ . '/../db/class.HandlerDB.php';
require_once __DIR__ . '/../class/function.globales.php';
require_once __DIR__ . '/../class/class.User.php';

/* @var Usuario $actualUser */
global $actualUser;

// Comprobamos que se ha accedido a esta api mediante POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    exit;
}

// Comprobamos que existe el campo tarea
$tarea = $_POST['tarea'] ?? null; //Si existe campo tarea, se asigna, sino , se asigna un null
if (is_null($tarea)) {
    header('Content-Type: application/json');
    echo json_encode(['exito' => 0, 'mensaje' => 'No se ha recibido ninguna tarea']);
    exit;
}


$respuesta = array();

switch($tarea) {
    case 'CHECK_PASSWORD_ACTUAL':
        // Obtenemos los datos enviados y vemos si existe el id de User

        //$userId = $_POST['id'] ?? null;
        $userId = $actualSession->read('id') ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Usuario no identificado']);
            exit;
        }
       

        $passwordActual = (isset($_POST['passwordActual']) && trim($_POST['passwordActual']) !== '') ? $_POST['passwordActual'] : null;

        if (is_null($passwordActual)){
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = "No ha escrito su contraseña actual";
            break;
        }

        $actualUser = new User($userId);
        if(!$actualUser-> checkPassword($passwordActual)){ //CheckPassword (en la clase User) verifica la contraseña del usuario ya presente en la base de datos
            $actualUser-> setFailedAttempts($actualUser -> getFailedAttempts() + 1);
            $actualUser-> save();

            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Contraseña actual incorrecta';
            break;
        }
        else{
            $respuesta['exito'] = 1 ;
            $respuesta['mensaje'] = 'Contraseña actual correcta';
               
        }
        break;
        

    case 'CAMBIAR_PASSWORD':
        // Obtenemos los datos enviados
        $passwordActual = (isset($_POST['passwordActual']) && trim($_POST['passwordActual']) !== '') ? $_POST['passwordActual'] : null;
        $password1 = (isset($_POST['password1']) && trim($_POST['password1']) !== '') ? $_POST['password1'] : null;
        $password2 = (isset($_POST['password2']) && trim($_POST['password2']) !== '') ? $_POST['password2'] : null;
        
        
        if (is_null($password1) || is_null($password2)){
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Debe rellenar la nueva contraseña y repetirla';
            break;
        }
        
        if($password1 != $password2 ){
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Las contraseñas no coinciden'; 
            break;
        }
        if (!checkPasswordRequirements($password1, $password2)){ //checkPasswordRequirements (en function.globales) verifica los requirimientos mínimos de la contraseña: Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.
            $respuesta['exito'] = 2;
            $respuesta['mensaje'] = 'Su contraseña no cumple los requisitos de seguridad'; 
            break;
        }
        if (!checkPasswordOldnotNew($passwordActual,$password1)){
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'La contraseña nueva no puede ser la misma que la antigua'; 
            break;
        }  

        if(checkPasswordRequirements($password1, $password2) && checkPasswordOldnotNew($passwordActual,$password1) && $actualUser-> checkPassword($passwordActual)){
            
            $actualUser -> setPassword($password1);
            $actualUser -> save();
            var_dump($actualUser -> save());
            //var_dump($actualUser);

            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = 'Contraseña modificada con éxito';
          
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Error en el cambio de contraseña';  
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