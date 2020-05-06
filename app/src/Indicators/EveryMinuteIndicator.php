<?php


namespace BBot\Indicators;


use BBot\TCPSocketRoutes;
use Ds\Vector;
use OutOfRangeException;
use UnderflowException;
use function abs;
use function printf;
use function sleep;

class EveryMinuteIndicator extends AbstractIndicator
{
    public function __construct(string $endpointstring)
    {
        parent::__construct($endpointstring);
        $this->startIndicator();
    }

    private function startIndicator()
    {
        while(true){
            printf('EveryMinuteIndicator  startCycle %s',PHP_EOL);
            sleep(60);
            $this->setState(!$this->getState());
            $this->notify();
        }
    }


}