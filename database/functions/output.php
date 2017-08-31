<?php
include_once './output/browser.php';
include_once './output/console.php';
include_once './output/csv.php';


function output($database, $header, $option)
{
    $functionName = "print" . $option;
    $functionName($database, $header);
}