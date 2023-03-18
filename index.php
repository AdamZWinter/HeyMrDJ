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

$f3->route('GET /charts', function () use ($f3) { Music::getCharts(); });

$f3->route('GET /dashboard', function () use ($f3) { HomePage::get(); });

// Event routes
$f3->route('GET /eventSearch', function (){ Events::get(); });
$f3->route('GET /events', function (){ Events::get(); });

//Defines route to error page
$f3->route('GET /error', function () use ($f3) {
    //$f3->error(400,'This is an error.');
    $view = new Template();
    echo $view->render("views/error.html");
});

//Calls and redirects to this route from client-side javascript will destroy the session
$f3->route('GET /destroy', function ($f3) {
    session_destroy();
//    setcookie("fname", "", time() - 3600);
//    setcookie("lname", "", time() - 3600);
//    $f3->reroute('home');
    HomePage::get();
});

//Run Fat Free
$f3->run();

