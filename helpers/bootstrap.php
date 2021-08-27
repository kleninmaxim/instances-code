<?php

set_time_limit(0);
ini_set("max_execution_time", 0);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/DB.php';

$connect = \src\DB::connect();

function debug($arr, $die = false)
{

    echo '<pre>' . print_r($arr, true) . '</pre>';

    if ($die) die;

}
