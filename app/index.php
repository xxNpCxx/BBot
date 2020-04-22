<?php

use BBot\Collectors\WebSocketCollector;


ini_set("default_socket_timeout", 1);

require __DIR__ . '/vendor/autoload.php';

//TODO
//exchange
//pair
//type

$collector = new WebSocketCollector('bookTicker', 'btc', 'usdt');
$collector->run();

