<?php

use ccxt\Exchange;
use src\DB;

try {
	require_once __DIR__ . '/helpers/bootstrap.php';

	$qty_symbols = 10;

	foreach (Exchange::$exchanges as $key => $exchange) {
	    $exchange_class = "\\ccxt\\$exchange";

		echo 'Exchange start: ' . $exchange . PHP_EOL;

		try {
		    $ex = new $exchange_class(["enableRateLimit" => false]);

		    $ex->load_markets();

			$symbols = $ex->symbols;

			if (!empty($symbols)) {
				$symbols = array_slice($symbols, 0, $qty_symbols);

				$all = count($symbols);

				for ($i = $all; $i < $qty_symbols; $i++) {
					$symbols[] = $symbols[$i - $all];
				}

			    $times = [];

			    foreach($symbols as $symbol) {
					try {
						usleep(100000);

				        $start = hrtime(true);

				        $ex->fetch_order_book($symbol);

				        $end = hrtime(true);

				        $times[] = ($end - $start) / 1000;
			    	} catch (Throwable $e) {
						continue;
					}
			    }

			    if (!empty($times)) {
				    $max = max($times);

				    $min = min($times);

				    $average = array_sum($times) / count($times);

				    DB::insertPing($exchange, $max, $min, $average);
			    } else {
					DB::insertPing($exchange);
				}

			} else {
				DB::insertPing($exchange);
			}

		} catch (Throwable $e) {
			DB::insertPing($exchange);
		}

		echo 'Exchange done: ' . $exchange . PHP_EOL;
	}

} catch (Throwable $e) {
    echo '[' . date('Y-m-d H:i:s') . '] Get Throwable: ' . $e->getMessage() . PHP_EOL;
	DB::updateRegionQueue();
}

DB::updateRegionQueue();

echo 'Done' . PHP_EOL;
