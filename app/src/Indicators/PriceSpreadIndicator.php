<?php


namespace BBot\Indicators;


use BBot\TCPSocketRoutes;
use Ds\Vector;
use OutOfRangeException;
use UnderflowException;
use function abs;

class PriceSpreadIndicator extends AbstractIndicator
{

    const SPREAD_STEPS = 10;
    const SPREAD_PERCENT_TO_ON = 0.04;
    private $priceChangeVector;

    public function __construct(?string $endpointstring, ?array $routes = [TCPSocketRoutes::ROUTE_BEST_BID_PRICE])
    {
        parent::__construct($endpointstring, $routes);
        $this->priceChangeVector = new Vector();
        $this->priceChangeVector->allocate(self::SPREAD_STEPS);
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


}