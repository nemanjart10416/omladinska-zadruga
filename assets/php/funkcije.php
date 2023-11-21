<?php
ini_set('session.cookie_httponly', 1);  //The 'httpOnly' flag
session_name('__Secure-PHPSESSID'); //cookies that have the __Secure- prefix:
session_start(['cookie_lifetime' => 43200, 'cookie_secure' => true, 'cookie_httponly' => true, 'cookie_samesite' => "Strict"]); //session

include_once("classes/load.php");
include_once("errors.php");
include_once("headers.php");

?>