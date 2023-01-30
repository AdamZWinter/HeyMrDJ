<?php
//index.php
//This is the routing controller

//Turn on error reporting

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require autoload file
require_once('vendor/autoload.php');

//Instantiate the F3 Base class
$f3 = Base::instance();

//Define a default route
$f3->route('GET /', function (){
    //Instantiate a view
    $view = new Template();
    echo $view->render("views/home.html");
});

//Define a route to order form 1
//$f3->route('GET|POST /order1', function () use ($f3) {
//    if ($_SERVER['REQUEST_METHOD'] ==  'POST'){
//        //Move data from POST array to SESSION array
//        $_SESSION['meal'] = $_POST['meal'];
//        $_SESSION['fname'] = $_POST['fname'];
//        $f3->reroute('summary');
//    }
//    //Instantiate a view
//    $view = new Template();
//    echo $view->render("views/order-form1.html");
//});



//Run Fat Free
$f3->run();

