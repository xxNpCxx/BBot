<?php

use BBot\Exchange\AbstractClient;
use BBot\Exchange\Client;

require __DIR__ . '/vendor/autoload.php';

printf('Usage: php strategy.php exchange=binance strategyName=Test mainSymbol=xrp quoteSymbol=btc accountEmail=xxNpCxx@gmail.com %s', PHP_EOL);

foreach ($argv as $arg) {
    [$key, $value] = explode('=',$arg);
    $$key = $value;
}

$strategyName = ucfirst($strategyName);
$strategyClass = sprintf('\BBot\Strategy\%sStrategy',$strategyName);

/** @var Client|AbstractClient $strategy */
/** @var string $exchangeName */
/** @var string $accountEmail */
/** @var string $mainSymbol */
/** @var string $quoteSymbol */

$strategy = new $strategyClass($exchange, $accountEmail, $mainSymbol, $quoteSymbol);