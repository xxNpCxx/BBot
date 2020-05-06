<?php


namespace BBot\Indicators;


use BBot\TCPSocketRoutes;
use BBot\ZMQSubscriber;
use SplObserver;
use function printf;
use function spl_object_id;

class AbstractIndicator extends ZMQSubscriber implements Indicator
{

    /**
     * @var SplObserver[]
     */
    private $subscribers;

    /**
     * @var bool
     */
    private $state;


    public function check($dataItem)
    {
        //some logic
        printf('Не реализован метод check() %s'.PHP_EOL);
        return false;
    }

    public function __construct(string $endpointstring, array $routes = [TCPSocketRoutes::ROUTE_ALL])
    {
        parent::__construct($endpointstring, $routes);
        $this->subscribers = [];
        $this->state = false;
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

    protected function onDataReceived(string $route, string $data)
    {
        printf('.');
        if ($this->check($data) === true){
            $this->notify();
        }
    }
    public function isStateChange(bool $state): bool
    {
        if($this->state !== $state){
            $this->setState($state);
            $stateMessage = $state ? 'Активирован' : 'Деактивирован';
            printf('[%s] %s%s', static::class, $stateMessage, PHP_EOL);
            return true;
        }
        return false;
    }
    public function getState(): bool
    {
        return $this->state;
    }

    public function setState(bool $newState)
    {
        $this->state = $newState;
    }

}