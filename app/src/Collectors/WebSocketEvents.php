<?php


namespace BBot\Collectors;


interface WebSocketEvents
{
    const EVENT_MESSAGE = 'message';
    const EVENT_PING = 'ping';
    const EVENT_ERROR = 'error';
    const EVENT_CLOSE = 'close';
}