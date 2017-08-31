<?php
include_once BASE_DIR . "/operations/select.php";
include_once 'output.php';
include_once BASE_DIR . '/operations/join.php';

function executeCommands($commands, $ouput)
{
    $header = [];
    $dataBase = [];
    openDataBase($commands["from"], $header, $dataBase);
    joinTables($dataBase, $commands["join"], $header);
    ///-------------------this is new
    //nu merge pt select gol din form pt ca primeste de fapt string gol
    //if (isset($commands["select"]) === true ) {
    //    $header = select($commands["select"], $header);
    //}
    //---------------------
    output($dataBase, $header, $ouput);
}

function openDataBase($filename, &$header, &$dataBase)
{
    $file = myReadFile($filename);
    parseFile($file, $header, $dataBase);
}

function myReadFile($filename)
{
    $toRead = fopen(BASE_DIR . '/database/' . $filename, "r", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array();
    $iterator = 0;

    while (!feof($toRead)) {
        $lines[$iterator] = fgetcsv($toRead);
        $iterator++;
    }
    fclose($toRead);

    return $lines;
}

function parseFile($file, &$header, &$dataBase)
{
    $lineLimit = count($file[0]);
    $linesLimit = count($file);
    moveIdToFront($file);

    for ($iterator = 1; $iterator < $lineLimit; $iterator++) {
        $header[$iterator - 1] = $file[0][$iterator];
    }

    for ($linesIterator = 1; $linesIterator < $linesLimit; $linesIterator++) {
        $key = $file[$linesIterator][0];
        $toPush = array();
        for ($lineIterator = 1; $lineIterator < $lineLimit; $lineIterator++) {
            $toPush[$header[$lineIterator - 1]] = $file[$linesIterator][$lineIterator];
        }
        $dataBase[$key] = $toPush;
    }
}

function parseJoinFile($file, &$header, &$dataBase, $tableName)
{
    $lineLimit = count($file[0]);
    $linesLimit = count($file);
    moveJoinIdToFront($file);


    for ($iterator = 1; $iterator < $lineLimit; $iterator++) {
        $header[$iterator - 1] = $tableName . '.' . $file[0][$iterator];
    }
    for ($linesIterator = 1; $linesIterator < $linesLimit; $linesIterator++) {
        $key = $file[$linesIterator][0];
        $toPush = array();
        if (empty($dataBase[$key]) == true) {
            $dataBase[$key] = [];
        }
        for ($lineIterator = 1; $lineIterator < $lineLimit; $lineIterator++) {
            $toPush[$header[$lineIterator - 1]] = $file[$linesIterator][$lineIterator];
        }
        array_push($dataBase[$key], $toPush);
        //var_dump($dataBase[$key]);
    }
    foreach ($dataBase as $userId => $children) {
        //var_dump($children);
        //$array = array_values($children);
        //unset($children);
        //$children = [];
        //$children[$tableName] = $array;
        $dataBase[$userId][$tableName] = $children;
        foreach (array_keys($dataBase[$userId]) as $key) {
            if ($key !== $tableName) {
                unset($dataBase[$userId][$key]);
            }
        }
    }
    //var_dump($dataBase);
}


function moveIdToFront($file)
{
    $idPosition = findIdPosition($file[0]);
    foreach ($file as $line) {
        $moveId = $line[$idPosition];
        unset($line[$idPosition]);
        array_unshift($line, $moveId);
    }
}

function moveJoinIdToFront($file)
{
    $idPosition = findJoinIdPosition($file[0]);
    foreach ($file as $line) {
        $moveId = $line[$idPosition];
        unset($line[$idPosition]);
        array_unshift($line, $moveId);
    }
}


function findIdPosition($line)
{
    $limit = count($line);
    for ($iterator = 0; $iterator < $limit; $iterator++) {
        if (strpos($line[$iterator], "id") !== false) {//probably better with just ==
            return $iterator;
        }
    }
    return $iterator;
}

function findJoinIdPosition($line)
{
    $limit = count($line);
    for ($iterator = 0; $iterator < $limit; $iterator++) {
        if (strpos($line[$iterator], "_id") !== false) {//will not work in case a have more foreign keys
            return $iterator;
        }
    }
    return $iterator;
}