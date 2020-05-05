<?php


namespace BBot\Exchange;


use BBot\Exchange\Binance\BinanceOrderClient;
use function sprintf;

/**
 * Общая реализация для клиентов реальных торговых бирж
 */
class AbstractClient implements Client
{
    /**
     * @var OrderClient|BinanceOrderClient
     */
    protected $orderClient;
    /**
     * @var Account
     */
    private $account;
    /**
     * @var string
     */
    private $exchangeName;

    public function __construct(string $exchangeName, array $options = [])
    {
        //TODO Реализовать отдельный класс для проверки опций

        $this->setExchangeName($exchangeName);
        $this->setOrderClient();
        $this->setAccount($options['account']);
    }

    public function setOrderClient()
    {
        $orderClientClassName = sprintf(
            '\BBot\Exchange\%s\%sOrderClient',
            $this->exchangeName,
            $this->exchangeName
        );

        if ($orderClientClassName instanceof OrderClient){
            $this->orderClient = new $orderClientClassName();
        }
    }

    public function setAccount(array $options)
    {

        $accountClassName = sprintf(
            '\BBot\Exchange\%s\%sAccount',
            $this->exchangeName,
            $this->exchangeName
        );

        if ($accountClassName instanceof Account){
            $this->account = new $accountClassName();
        }

        $this->account->setApiKey($options['apiKey']);
        $this->account->setSecretKey($options['secretKey']);
        $this->account->setEmail($options['email']);
    }
    public function getExchangeName():string
    {
        return $this->exchangeName;
    }
    public function setExchangeName(string $newExchangeName)
    {
        $this->exchangeName = $newExchangeName;
    }

}