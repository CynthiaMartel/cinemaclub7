<?php

require_once __DIR__ . '/../config/config.globales.php';
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
    case 'CREATE_ACCOUNT':
        $newUser = new User(); // sin pasar ID porque el User se crea de cero
    
        $name = sanitizarString(trim($_POST["name"]));
        $email = sanitizarString(trim($_POST["email"]));
        $password1 = sanitizarString(trim($_POST["password"]));
        $password2= sanitizarString(trim($_POST["repeatedPassword"]));
    
        if (strlen($name) == 0) {
            $respuesta['exito'] = 0;
            $respuesta['errorName'] = 1;
            $respuesta['mensaje'] = 'Debes rellenar tu nombre';
            break;
        }
    
        $newUser->setName($name);
    
        if (strlen($email) == 0) {
            $respuesta['exito'] = 0;
            $respuesta['errorMail'] = 1;
            $respuesta['mensaje'] = 'Debes rellenar tu mail';
            break;
        }
        
        $newUser->setEmail($email);
        //PONER AQUÍ VALIDACIÓN DE EMAIL CON MÉTODOS YA CREADOS!!!!!!!!!    
    
        if (strlen($password1) == 0) {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Debes rellenar el campo contraseña';
            break;
        }
    
        if (strlen($password2) == 0) {
            $respuesta['exito'] = 0;
            $respuesta['errorRepeatedPassword'] = 1;
            $respuesta['mensaje'] = 'Debes repetir la contraseña';
            break;
        }

        if ($password1 !=  $password2){
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Las contraseñas no coinciden'; 
            break;
        }

        if(checkPasswordRequirements($password1, $password2)){
            
            $newUser -> setPassword($password1);
            $newUser -> setIdRol(3); //  3 = 'User' como usuario distinto de Editor o Admin para que los nuevos usuarios queden registrados como usuarios regulares
            
            $newUser -> save();

            // Crear la sesión
            require_once __DIR__ . '/../class/class.SecureSessionHandler.php'; 
            $sesion = new SecureSessionHandler();
            $sesion->start();
            $sesion->write('id', $newUser->getidUser());
            $sesion->write('ip', getUserIp());


            $respuesta['exito'] = 1;
            $respuesta['mensaje'] = '¡Éxito en la creación de la nueva cuenta! ¡Te damos la bienvenida! Cargando...';
          
        } else {
            $respuesta['exito'] = 0;
            $respuesta['mensaje'] = 'Error en la creación de nuevo usuario';  
        }
        break;
    
    default:
    $respuesta['exito'] = 0;
    $respuesta['mensaje'] = 'Error en la petición de guardar al nuevo usuario';
    break;
    }    

//ob_clean();
header('Content-Type: application/json');
echo json_encode($respuesta);


