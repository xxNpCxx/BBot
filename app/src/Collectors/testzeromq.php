<?php


namespace BBot\Collectors;


use ZMQ;
use ZMQContext;
use ZMQSocket;

class testzeromq
{
    /**
     * testzeromq constructor. client
     * @throws \ZMQSocketException
     */
    public function __construct()
    {
        $subscriber = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_SUB);
        $subscriber->connect('ws://stream.binance.com:9443/ws/ethbtc@bookTicker');
        print_r($subscriber->getEndpoints());
        $message = $subscriber->recv();
        print_r($message);
        $subscriber->disconnect('ws://stream.binance.com:9443/ws/ethbtc@bookTicker');
    }



    public function startServer()
    {
        $context = new ZMQContext();
        $publisher = new ZMQSocket($context, ZMQ::SOCKET_PUB);
        $publisher->bind("tcp://*:5563");
    }
}