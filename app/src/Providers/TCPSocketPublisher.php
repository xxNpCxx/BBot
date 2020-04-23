<?php


namespace BBot\Providers;


use ZMQ;
use ZMQContext;
use ZMQSocket;
use function printf;

class TCPSocketPublisher
{
    const DEFAULT_IP = '*';
    const DEFAULT_PORT = 5563;

    /**
     * @var ZMQSocket
     */
    private $publisher;

    public function __construct($ip = self::DEFAULT_IP, $port=self::DEFAULT_PORT)
    {
        $this->port = $port;
        $this->ip = $ip;

        $this->startServer();
    }

    /**
     * Отправляет по маршруту $route данные $data
     * $route нужен для фильтрации отправляемых данных.
     *
     * @param string $route
     * @param string $data
     * @throws \ZMQSocketException
     */
    public function send(string $route, string $data)
    {
        $this->publisher->send($route,ZMQ::MODE_SNDMORE);
        $this->publisher->send($data,ZMQ::MODE_DONTWAIT);
    }

    private function startServer()
    {
        $context = new ZMQContext();
        $this->publisher = new ZMQSocket($context, ZMQ::SOCKET_PUB);
        $endpoint = printf('tcp://%s:%s',$this->ip, $this->port);
        $this->publisher->bind($endpoint);
    }

}