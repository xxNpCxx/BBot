<?php


namespace BBot;

use ZMQ;
use ZMQContext;
use ZMQSocket;
use function printf;

class ZMQPublisher
{
    /**
     * @var ZMQSocket
     */
    private $publisher;
    private $endpoint;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;

        $this->startServer();

        printf('Binded to [%s] %s', $this->endpoint, PHP_EOL);
    }

    /**
     * Отправляет по маршруту $route данные $data
     * $route нужен для фильтрации отправляемых данных.
     *
     * @param string $data
     * @param string $route
     * @throws \ZMQSocketException
     */
    public function send(string $data, string $route = TCPSocketRoutes::ROUTE_ALL)
    {
        $this->publisher->send($route,ZMQ::MODE_SNDMORE);
        $this->publisher->send($data,ZMQ::MODE_DONTWAIT);
    }

    private function startServer()
    {
        $context = new ZMQContext();
        $this->publisher = new ZMQSocket($context, ZMQ::SOCKET_PUB);
        $this->publisher->bind($this->endpoint);
    }

}