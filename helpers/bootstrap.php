<?php

use src\DB;

set_time_limit(0);
ini_set("max_execution_time", 0);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/DB.php';

$connect = DB::connect();

function debug($arr, $die = false): void
{
    echo '<pre>' . print_r($arr, true) . '</pre>';

    if ($die)
        die();
}