<?php


namespace BBot\Exchange\Binance;


use BBot\Exchange\Account;

class BinanceAccount implements Account
{

    private $apiKey;
    private $secretKey;
    private $email;

    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
}