<?php

function getCliArguments() {
    return getopt("", [
        'select:',
        'from:',
        'join:',
        'where:'
    ]);
}

function getQueryArguments()
{
    return $_GET;
}

function getPostArguments()
{
    return $_POST;
}