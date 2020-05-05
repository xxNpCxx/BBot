<?php


namespace BBot\Exchange\Binance;


use BBot\Exchange\OrderClient;
use ccxt\binance;

class BinanceOrderClient extends binance implements OrderClient
{
    public function __construct($options = array())
    {
        $ccxtOptions = [
            'apiKey' => $options['apiKey'],
            'secret' => $options['secretKey'],
            'verbose' => true,
        ];
        parent::__construct($ccxtOptions);
    }

}