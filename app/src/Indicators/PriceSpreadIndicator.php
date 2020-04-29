<?php


namespace BBot\Indicators;


use BBot\TCPSocketRoutes;
use BBot\ZMQSubscriber;
use Cassandra\Exception\DivideByZeroException;
use DivisionByZeroError;
use Ds\Vector;
use OutOfRangeException;
use SplObserver;
use SplSubject;
use UnderflowException;
use function abs;
use function var_dump;

class PriceSpreadIndicator extends ZMQSubscriber implements SplSubject
{

    const SPREAD_STEPS = 10;
    const SPREAD_PERCENT_TO_ON = 0.04;
    private $priceChangeVector;
    /**
     * @var SplObserver[]
     */
    private $subscribers;
    private $state;

    public function __construct(string $endpointstring, $route = TCPSocketRoutes::ROUTE_BEST_BID_PRICE)
    {
        parent::__construct($endpointstring, $route);
        $this->state = false;
        $this->subscribers = [];
        $this->priceChangeVector = new Vector();
        $this->priceChangeVector->allocate(self::SPREAD_STEPS);
    }

    protected function onDataReceived(string $data)
    {
        if ($this->check($data) === true){
            $this->notify();
        }
    }

    public function check($dataItem)
    {
        //Пока вектор наполняется данными мы будем ловить исключения
        //Специально сделал так чтобы не делать лишних проверок каждый раз
        try{
            $this->priceChangeVector->push($dataItem);
            $lastElement = $this->priceChangeVector->get(self::SPREAD_STEPS-1);
        }catch (UnderflowException|OutOfRangeException $e){
            return false;
        }
        $firstElement = $this->priceChangeVector->shift();
        $spreadChangePercent = (float)abs(($lastElement - $firstElement)/($lastElement/100));
        printf('Спред %f%% %s',$spreadChangePercent, PHP_EOL);
        $toogledCondition = $spreadChangePercent >= self::SPREAD_PERCENT_TO_ON;

        return $this->isStateChange($toogledCondition);
    }

    public function attach(SplObserver $observer)
    {
        $this->subscribers[spl_object_id($observer)] = true;
    }

    public function detach(SplObserver $observer)
    {
        unset($this->subscribers[spl_object_id($observer)]);
    }

    public function notify()
    {
        foreach ($this->subscribers as $subscriber)
        {
            $subscriber->update($this);
        }
    }

    public function isStateChange(bool $state)
    {
        if($this->state !== $state){
            $this->state = $state;
            $stateMessage = $state ? 'Активирован' : 'Деактивирован';
            printf('[%s] %s%s', self::class, $stateMessage, PHP_EOL);
            return true;
        }
        return false;
    }
}