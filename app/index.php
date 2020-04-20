<?php

use BBot\Collectors\WebSocketCollector;
use MongoDB\Client;
use MongoDB\Driver\Exception\ConnectionTimeoutException;

ini_set("default_socket_timeout", 1);

require __DIR__ . '/vendor/autoload.php';

//    $collector = new testzeromq();


//$collector = new WebSocketCollector('bookTicker', 'eth', 'btc');
//$collector->run();



//TODO
//exchange
//pair
//type
$client = (new Client(
    'mongodb://root:ggg12345678ggg@mongodb:27017'
));

try {
    $dbs = $client->listDatabases();
} catch (ConnectionTimeoutException $e) {
    var_dump($e->getMessage());
    // PHP cannot find a MongoDB server using the MongoDB connection string specified
    // do something here
}

var_dump($dbs);

//$result = $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );

/*
$collector = new WebSocketCollector('bookTicker', 'btc', 'usdt');
$collector->run();*/

