<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//start the session
session_start();


//require autoload file
require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');

//create an instance of the Base class
$f3 = Base::instance();


//define a default root
$f3->route("GET /", function($f3){

    $view = new Template();
    echo $view->render('views/home.html');

});

//define a survey route
$f3->route("GET|POST /survey", function($f3){

    //Get the condiments from the model and add to F3 hive
    $f3->set('options', getOptions());
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['options'] = $_POST['options'];

    //If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //Add the data to the session variable
        //If condiments were selected
        if (isset($_POST['options'])) {

            $_SESSION['options']  = implode(", ",$_POST['options']);

        }
        else {

            $_SESSION['options'] = "None selected";
        }

        //Redirect user to summary page
            $f3->reroute('summary');

    }

    $view = new Template();
    echo $view->render('views/survey.html');

});


//Define a summary route
$f3->route('GET /summary', function() {

    $view = new Template();
    echo $view->render('views/summary.html');

    //Clear the session data
    session_destroy();
});

//run fat free
$f3->run();