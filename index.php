<?php
//index.php
//This is the routing controller

//Turn on error reporting

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload file
//Do this before session start because session has an object that will not work
// if the class has not been loaded already
require_once('vendor/autoload.php');

//Start a session
session_start();

//Instantiate the F3 Base class
$f3 = Base::instance();

//https://fatfreeframework.com/3.5/routing-engine#TheF3Autoloader
$f3->set('AUTOLOAD','controllers/');

//Define a default route
$f3->route('GET /', function (){ HomePage::get(); });
$f3->route('GET /home', function (){ HomePage::get(); });
$f3->route('GET /register', function () use ($f3) { HomePage::getRegister($f3); });
$f3->route('POST /register', function () use ($f3) { HomePage::postRegister($f3); });
$f3->route('GET /signIn', function () use ($f3) { HomePage::getSignIn($f3); });
$f3->route('POST /signIn', function () use ($f3) { HomePage::postSignIn($f3); });

$f3->route('GET /dashboard', function () use ($f3) { HomePage::get(); });

// Event routes
$f3->route('GET /eventSearch', function (){ Events::get(); });
$f3->route('GET /events', function (){ Events::get(); });

//Run Fat Free
$f3->run();

