<?php

use BBot\Indicators\PriceSpreadIndicator;

ini_set("default_socket_timeout", 1);

require __DIR__ . '/vendor/autoload.php';

echo "\n\r\n Usage php indicator.php exchange=binance mainSymbol=btc quoteSymbol=usdt type=bookTicker \n\r\n";

foreach ($argv as $arg) {
    [$key, $value] = explode('=',$arg);
    $$key = $value;
}

/** @var string $exchange */
/** @var string $mainSymbol */
/** @var string $quoteSymbol */
/** @var string $type */

$endpoint = sprintf(
    'ipc://%s-%s-%s-%s.ipc',
    $exchange,
    $mainSymbol,
    $quoteSymbol,
    $type
);



$indicator = new PriceSpreadIndicator($endpoint);
$indicator->listen();