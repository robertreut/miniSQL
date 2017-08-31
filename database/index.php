<?php

define("BASE_DIR", '/var/www/database');
include_once 'functions/inputGetters.php';
include_once 'functions/validator.php';
include_once 'functions/commands.php';


//echo $_POST["from:"];
//echo $_POST["join:"];
//echo $_POST["select:"];
//echo $_POST["output:"];

$ouput = '';
if (php_sapi_name() === 'cli') {
    $arguments = getCliArguments();
    $ouput = 'console';
} elseif (!empty($_POST)) {
    $arguments = getPostArguments();
    $ouput = 'browser';
} else {
    $arguments = getQueryArguments();
    $ouput = 'browser';
}

$arguments['join']=explode(',',$arguments['join']);

if (validateCommands($arguments) == false) {
    return 0;
}

//var_dump($commands);
executeCommands($arguments, $ouput);

