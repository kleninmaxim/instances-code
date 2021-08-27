<?php

require_once dirname(__DIR__) . '/www/helpers/bootstrap.php';

$exchange_id = 'binance';

$exchange_class = "\\ccxt\\$exchange_id";

$exchange = new $exchange_class();

$exchange->load_markets();

$order_book = $exchange->fetch_order_book('BTC/USDT');

debug($order_book);

$regions = \src\DB::getAllRegions();

debug($regions);
