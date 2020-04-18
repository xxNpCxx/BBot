<?php

use BBot\Collectors\WebSocketCollector;

ini_set("default_socket_timeout", 1);

require __DIR__ . '/vendor/autoload.php';

//    $collector = new testzeromq();


$collector = new WebSocketCollector('bookTicker', 'eth', 'btc');
$collector->run();
