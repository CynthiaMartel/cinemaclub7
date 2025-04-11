<?php
require_once __DIR__.'/../config/config.globales.php';
require_once __DIR__.'/../class/class.Usuario.php';

$usuario = new Usuario(1);

echo "<pre>";
print_r($usuario);
echo "</pre>";

$usuario->setPassword('Probando1234&');
$usuario->save();