<?php
setlocale(LC_TIME, 'es_ES.UTF-8');
date_default_timezone_set('Atlantic/Canary');

// CONFIGURACIÓN GENERAL
const CONFIG_GENERAL = array(
    'TITULO_WEB' => 'CinemaClub7',
    'DESCRIPCION_WEB' => 'Ejemplo de login para una consulta odontológica',
    'RUTA_URL_BASE' => 'http://localhost/cinemaclub7',
    'RUTA_URL_BASE_LIB' => 'http://localhost/cinemaclub7/lib',
    'RUTA_SERVER_BASE' => __DIR__.'/..',
    'RUTA_SERVER_BASE_LIB' => __DIR__.'/../lib',
    'RUTA_LOGIN' =>'http://localhost/cinemaclub7/modal.login.php',
    'RUTA_MENU' =>'http://localhost/cinemaclub7/menu/menu.php',


    'MAX_FAILED_ATTEMPS' => 5,

    'ROLES' => array('ADMIN', 'EDITOR', 'USER')
);

// PARÁMETROS SESIONES
const CONFIG_SESIONES = array(
    'NOMBRE_SESION_LOGIN' => 'CICinemaClub7',
    'DOMINIO_SESION_LOGIN' => 'localhost'
);

const PASSWORD_LONGITUD_MINIMA = 8;

// PARÁMETROS BASE DE DATOS
const CONFIG_DB = array(
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PASSWORD' => '',
    'DB_NAME' => 'cinemaclub7',
    'DB_LOG_FILE' => __DIR__.'/../log/db.log'
);