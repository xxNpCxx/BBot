<?php

require __DIR__ . '/vendor/autoload.php';


$context = new ZMQContext();
$subscriber = new ZMQSocket($context, ZMQ::SOCKET_SUB);
$subscriber->connect("tcp://localhost:5563");
$subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "Y");

while (true) {
    //  Read envelope with address
    $address = $subscriber->recv();
    //  Read message contents
    $contents = $subscriber->recv();
    printf ("[%s] %s%s", $address, $contents, PHP_EOL);
}
//
//$key = $this->mainSymbol . $this->quoteSymbol;
//$collection = $this->mongoClient->selectCollection('local', 'binance');
//$collection->insertOne(
//    [
//        $key => $jsonMessage
//    ]
//);

