<?php

/*
require_once __DIR__.'/class/class.Usuario.php';

$usuario = new Usuario(1);
echo "<pre>";
var_dump($usuario);
echo "</pre>";

$usuario->setPassword('Probando12345&');

echo "<pre>";
var_dump($usuario);
echo "</pre>";
$usuario->save();
*/

require_once __DIR__.'/class/class.Paciente.php';

$paciente = new Paciente(1);
$paciente->setNif(null);
$paciente->setNombre('CARLOS');
$paciente->setApellidos('PÉREZ DÍAZ');
$paciente->setEmail(null);
$paciente->setTelefonoMovil('600101010');

var_dump($paciente->save());
var_dump($paciente->eliminar());