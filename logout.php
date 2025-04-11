<?php
require_once(__DIR__."/class/class.SecureSessionHandler.php");

$sesion = new SecureSessionHandler();
$sesion->start();
$sesion->destroySession();
header('Location: '.CONFIG_GENERAL['RUTA_URL_BASE'].'/index.php');
