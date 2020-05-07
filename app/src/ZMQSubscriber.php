<?php


namespace BBot;


use ZMQ;
use ZMQContext;
use ZMQSocket;
use function printf;

abstract class ZMQSubscriber implements CanSubscribeToZMQ
{
    private $context;
    private $subscriber;
    private $routes;
    private $endpoint;


    protected function onDataReceived(string $route, string $data)
    {
        echo('onDataReceived'.PHP_EOL);
    }

    public function __construct( string $endpointstring, array $routes = [TCPSocketRoutes::ROUTE_ALL])
    {
        $this->endpoint = $endpointstring;
        $this->routes = $routes;
        $this->context = new ZMQContext();
        $this->subscriber = new ZMQSocket($this->context, ZMQ::SOCKET_SUB);
        foreach ($routes as $route) {
            $this->subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, $route);
        }
        $this->connect();
    }

    private function connect()
    {
        $this->subscriber->connect($this->endpoint);
        printf('Connection successfull to [%s]%s', $this->endpoint, PHP_EOL);

    }

    public function listen(): void
    {
        printf('Start listen [%s] route %s', $this->routes, PHP_EOL);
        while (true) {
            $route = $this->subscriber->recv();
            $contents = $this->subscriber->recv();
            $this->onDataReceived($route, $contents);
        }
    }
}