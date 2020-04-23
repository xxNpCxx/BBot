<?php


namespace BBot\Exchanges;


class BinanceExchange implements WebSocketExchangeData
{
    private $url;
    private $port;
    private $protocol;
    private $availableEndpoints;

    function getProtocol(): string
    {
        return $this->protocol;
    }

    function getPort(): int
    {
        return $this->port;
    }

    function getUrl(): string
    {
        return $this->url;
    }

    function getAvailableEndpoints(): array
    {
        return $this->availableEndpoints;
    }
}