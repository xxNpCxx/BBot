<?php


namespace BBot\Exchange\Binance;


use BBot\Exchange\Account;
use ccxt\binance;
use Symfony\Component\Yaml\Yaml;

class BinanceAccount extends binance implements Account
{

    private $_apiKey;
    private $_secretKey;
    private $_email;

    public function __construct($email)
    {
        $this->setEmail($email);
        $account = Yaml::parseFile(__DIR__.'/../../../accounts.yaml')['binance']['emails'][$email];

        $this->setApiKey($account['apiKey']);
        $this->setSecretKey($account['secretKey']);

        $ccxtOptions = [
            'apiKey' => $this->_apiKey,
            'secret' => $this->_secretKey,
            'verbose' => false
        ];

        parent::__construct($ccxtOptions);
    }

    public function setApiKey(string $apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    public function setSecretKey(string $secretKey)
    {
        $this->_secretKey = $secretKey;
    }

    public function setEmail(string $email)
    {
        $this->_email = $email;
    }

    public function getName()
    {
        return $this->_email;
    }

    public function getSecretKey()
    {
        return $this->_secretKey;
    }

    public function getApiKey()
    {
        return $this->_apiKey;
    }

    public function getBalance()
    {
        return $this->fetchBalance();
    }
}