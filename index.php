<?php

try {

	require_once __DIR__ . '/helpers/bootstrap.php';

	$exchanges = \ccxt\Exchange::$exchanges;

	$qty_symbols = 10;

	foreach ($exchanges as $key => $exchange) {

	    $exchange_class = "\\ccxt\\$exchange";

		echo 'Exchange start: ' . $exchange . PHP_EOL;

		try {

		    $ex = new $exchange_class();

		    $ex->load_markets();

		    date_default_timezone_set('UTC');

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

				        $start = hrtime(true);

				        $ex->fetch_order_book($symbol);

				        $end = hrtime(true);

				        $times[] = ($end - $start) / 1000;


			    	} catch (Exception $e) {

						continue;

					}

			    }

			    if (!empty($times)) {

				    $max = max($times);

				    $min = min($times);

				    $average = array_sum($times) / count($times);

				    \src\DB::insertPing($exchange, $max, $min, $average);

			    } else {

					\src\DB::insertPing($exchange);

				}

			} else {

				\src\DB::insertPing($exchange);

			}

		} catch (Exception $e) {

			\src\DB::insertPing($exchange);

		}

		echo 'Exchange done: ' . $exchange . PHP_EOL;

	}

} catch (Exception $e) {

	\src\DB::updateRegionQueue();

}

\src\DB::updateRegionQueue();

echo 'Done' . PHP_EOL;
