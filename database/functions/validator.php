<?php
function validateCommands($commands)
{
    //var_dump($commands["from"]);
    return validateFile($commands["from"]);
}

function validateFile($filename){
    if ($filename == false) {
        echo " No first table given. Please try again!";

        return false;
    }
    if (file_exists ( 'database/'.$filename) == false) {
        echo " From file does not exist. Please try again!";

        return false;
    }

    return true;
}
