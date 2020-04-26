<?php

use BBot\Collectors\MongoCollector;

ini_set("default_socket_timeout", 1);

require __DIR__ . '/vendor/autoload.php';

echo "\n\r\n Usage php collector.php exchange=binance mainSymbol=btc quoteSymbol=usdt type=bookTicker \n\r\n";


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

$collector = new MongoCollector($endpoint,$type);
$collector->listen();
