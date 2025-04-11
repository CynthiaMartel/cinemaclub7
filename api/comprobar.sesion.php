<?php

require_once __DIR__.'/../config/config.globales.php';
require_once __DIR__.'/../class/class.SecureSessionHandler.php';
require_once __DIR__.'/../class/class.User.php';

$actualSession = new SecureSessionHandler();
$actualSession->start();  


// INTENTAMOS LEER EL id DESDE SecureSessionHandler 
$userId = $actualSession->read('id');

$respuesta = array();
if ($actualSession->read('id') === false || $actualSession->read('ip') !== getUserIp()) {
    $actualSession->destroySession();
    if (isset($_POST['tarea'])) {
        $respuesta['exito'] = 0;
        $respuesta['mensaje'] = 'Su sesión ha caducado. Debe volver a hacer login.';
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    } else {
   /*      header('Location: '.CONFIG_GENERAL['RUTA_URL_BASE'].'/login.php');
        exit; */
    }
} else {
    $actualUser = new User($actualSession->read('id'));
    if ($actualUser->getBlocked()) {
        $actualSession->destroySession();
        header('Location: '.CONFIG_GENERAL['RUTA_URL_BASE'].'/login.php');
    }
}
