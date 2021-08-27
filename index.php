<?php

set_time_limit(0);
ini_set("max_execution_time", 0);

function debug($arr, $die = false)
{

    echo '<pre>' . print_r($arr, true) . '</pre>';

    if ($die) die;

}

require_once dirname(__DIR__) . '/www/vendor/autoload.php';

$exchange_id = 'binance';

$exchange_class = "\\ccxt\\$exchange_id";

$exchange = new $exchange_class();

$exchange->load_markets();

$order_book = $exchange->fetch_order_book('BTC/USDT');

debug($order_book);
