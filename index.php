<?php
//https://medium.com/@noufel.gouirhate/create-your-own-mvc-framework-in-php-af7bd1f0ca19
session_start();
define('BP',__DIR__ . DIRECTORY_SEPARATOR);
//echo BP;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$t = implode(PATH_SEPARATOR,
                [
                    BP . 'model',
                    BP . 'controller'
                ]);

//print_r($t);

set_include_path($t);

spl_autoload_register(function($klasa)
{
    $putanje = explode(PATH_SEPARATOR,get_include_path());
    foreach($putanje as $p){
        if(file_exists($p . DIRECTORY_SEPARATOR . $klasa . '.php' )){
            include $p . DIRECTORY_SEPARATOR . $klasa . '.php';
            break;
        }
    }
});

// https://www.php.net/manual/en/language.oop5.paamayim-nekudotayim.php
require 'vendor/autoload.php';
App::start();