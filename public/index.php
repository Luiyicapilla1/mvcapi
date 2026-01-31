<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use lgc\mvc2app\Core;

    //cargando el iniciador
    //require_once $_SERVER['DOCUMENT_ROOT'].'/ud5/mvc2app/app/iniciador.php';

 //   require_once $_SERVER['DOCUMENT_ROOT'].'/ud5/mvc2app/app/librerias/Dd.php';
    //require_once $_SERVER['DOCUMENT_ROOT'].'/ud5/mvc2app/app/librerias/Controlador.php';
    //require_once $_SERVER['DOCUMENT_ROOT'].'/ud5/mvc2app/app/librerias/Core.php';


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//require_once '/ud5/mvc2app/app/iniciador.php';
require_once __DIR__.'/../app/iniciador.php';

    //$i = __DIR__.'/../app/iniciador.php';
    
    //$BASE = $_SERVER['DOCUMENT_ROOT'];

    // Instanciamos el controlador
$iniciar = new Core();
