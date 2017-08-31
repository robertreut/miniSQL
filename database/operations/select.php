<?php

function select($selectOption, &$header)
{
     unset($header);
     $header = explode(",", $selectOption);

     return $header;
}