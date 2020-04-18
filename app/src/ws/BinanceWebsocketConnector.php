<?php

namespace BBot;

use React\Socket\ConnectionInterface;
use function Ratchet\Client\connect;

abstract class AbstractWebsocketDataCollector implements WebSocketConnectionInterface
{
    protected const BASE_WS_ENDPOINT = 'wss://stream.binance.com:9443/';
    private $binanceConnectionId = 1;
    private $endpoint;
    protected function getEndpoint(): string
    {
        return $this->endpoint;
    }



    public function __construct()
    {
        $this->endpoint = self::BASE_WS_ENDPOINT;
    }

}