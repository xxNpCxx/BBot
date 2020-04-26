<?php


namespace BBot\Collectors;


use BBot\TCPSocketRoutes;
use ZMQ;
use ZMQContext;
use ZMQSocket;
use function printf;
use function printf as printfAlias;
use function sprintf;
use function usort;

class TCPSocketSubscriber
{
    private $context;
    private $subscriber;
    private $route;
    private $endpoint;
    private $cb;


    protected function onDataReceived(string $data)
    {
        echo('onDataReceived'.PHP_EOL);
    }

    public function __construct( string $endpointstring, $route = TCPSocketRoutes::ROUTE_ALL)
    {
        $this->endpoint = $endpointstring;
        $this->route = $route;
        $this->context = new ZMQContext();
        $this->subscriber = new ZMQSocket($this->context, ZMQ::SOCKET_SUB);
        $this->subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, $this->route);
        $this->connect();
    }

    private function connect()
    {
        $this->subscriber->connect($this->endpoint);
        printfAlias('Connection successfull to [%s]%s', $this->endpoint, PHP_EOL);

    }

    public function listen()
    {
        printfAlias('Start listen [%s] route %s', $this->route, PHP_EOL);
        while (true) {
            $route = $this->subscriber->recv();
            $contents = $this->subscriber->recv();
            $this->onDataReceived($contents);
        }
    }
}