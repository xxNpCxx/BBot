<?php


namespace BBot;


interface WebSocketConnectionInterface
{
    //Открывает соединение с сервером вебсокетов
    public function connect(string $url): bool;
}