<?php

use BBot\Providers\ExchangeDataProvider;


ini_set("default_socket_timeout", 1);

require __DIR__ . '/vendor/autoload.php';

echo "Usage php index.php type=bookTicker mainSymbol=btc quoteSymbol=usdt\n";

foreach ($argv as $arg) {
    [$key, $value] = explode('=',$arg);
    $$key = $value;
}


$collector = new ExchangeDataProvider($type, $mainSymbol, $quoteSymbol);
$collector->run();

