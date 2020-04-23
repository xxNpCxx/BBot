<?php


namespace BBot\Exchanges;


interface WebSocketExchangeData
{
    function getProtocol();
    function getPort();
    function getUrl();
    function getAvailableEndpoints();
}