<?php


namespace BBot\Exchange;


use BBot\Exchange\Binance\BinanceOrderClient;
use function explode;
use function sprintf;
use function strpos;
use function substr;
use function ucfirst;
use function var_dump;

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
    protected $account;
    /**
     * @var string
     */
    private $exchangeName;

    public function __construct($exchangeName, $accountEmail)
    {
        //TODO Реализовать отдельный класс для проверки опций
        $this->setExchangeName($exchangeName);
        $this->setOrderClient();
        $this->setAccount($accountEmail);
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

    public function setAccount(string $email)
    {

        $accountClassName = sprintf(
            '\BBot\Exchange\%s\%sAccount',
            $this->exchangeName,
            $this->exchangeName
        );
        var_dump($accountClassName);
        var_dump(Account::class);
        var_dump($accountClassName instanceof Account);
//        if ($accountClassName instanceof Account){
            $this->account = new $accountClassName($email);
//        }
    }

    public function getExchangeName():string
    {
        return $this->exchangeName;
    }
    public function setExchangeName(string $newExchangeName)
    {
        $this->exchangeName = ucfirst($newExchangeName);
    }

    public function getStrategyName()
    {
        $classFullName = explode('\\', static::class);
        $className = array_pop($classFullName);
        $strategyName = substr($className,0,strpos($className,'Strategy'));

        return $strategyName;
    }

}