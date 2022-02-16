<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//start the session
session_start();


//require autoload file
require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');
require('model/validation-functions.php');

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
    $name = "";

    //If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = $_POST['name'];
        $options = $_POST['options'];

        //Validate the data
        if(validName($name)) {

            //Add the data to the session variable
            $_SESSION['name'] = $_POST['name'];
        }
        else {

            //Set an error
            $f3->set("errors['name']", 'Please enter a name.');
        }
        //If options were selected
        if (isset($_POST['options'])) {

            $_SESSION['options'] = $_POST['options'];

            //If options are valid
            if (validOptions($options)) {
                $options = implode(", ", $_POST['options']);
            }
            else {
                $f3->set("errors['options']", "Invalid selection of options");
            }
        }
        else {


            $f3->set("errors['options']", "Please select an option.");
        }

        //Redirect user to summary page
        if (empty($f3->get('errors'))) {
            $_SESSION['options'] = $options;
            $_SESSION['name'] = $name;
            $f3->reroute('summary');
        }

    }
    $f3->set('name', $name);
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