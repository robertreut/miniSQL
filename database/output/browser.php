<?php

include_once "/var/www/database/functions/array.php";

function printBrowser($database, $header)
{
    echo '<table style="border-collapse: collapse">';
    printBrowserHeader($header);
    printBrowserRows($database, $header);
    echo "</table>";
}

function printBrowserHeader($header)
{
    echo "<tr>";
    array_map(function ($element) {
        echo "<th style='border:1px solid black'>", $element, "</th>";
    }, $header);
    echo "</tr>";
}

function printBrowserRows($database, $header)
{
    foreach ($database as $user) {
        echo "<tr>";
        $i = 0;
        printB($user, $header, $i);
        echo "</tr>";
    }
}

//
//function prepareNestedArray($array, $column)
//{
//    echo "<pre>";
//    //var_dump($array);
//    var_dump(array_column($array, "name"));
//}

function printBrowserData($string)
{
    echo "<td  style='border:1px solid black'>", $string, "</td>";
    return 0;
}

function printB($entity, $header, &$position)
{
    foreach ($entity as $information) {
        if (is_array($information) != true) {
            if($information=='')
            {
                printBrowserData("NULL");
                $position++;
            }
            else {
                printBrowserData($information);
                $position++;
            }
        }
        else {
            if (array_depth($information) == 2) {
                $numberOfFields = count($information[0]);
                $arrayKey = array_keys($information[0]);
                for ($i = 0; $i < $numberOfFields; $i++) {
                    if (strcmp($arrayKey[$i], $header[$position]) == 0) {
                        printBrowserData(implode(",", array_column($information, $header[$position])));
                    } else {
                        printBrowserData("NULL");
                        $i--;
                    }
                    $position++;
                }
            }
            if (array_depth($information) > 2) {
                printB($information, $header, $position);
            }
        }
    }
    for ($i = 0, $length = count($header) - $position; $i < $length; $i++) {
        printBrowserData("NULL");
    }
}