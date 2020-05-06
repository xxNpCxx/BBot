<?php


namespace BBot\Strategy;

use BBot\Exchange\AbstractClient;
use BBot\Indicators\Indicator;
use SplObserver;
use SplSubject;
use Symfony\Component\Yaml\Yaml;
use function get_class;
use function lcfirst;
use function ucfirst;
use function var_dump;

/**
 * Тестовая стратегия
 */
class TestStrategy extends AbstractClient implements SplObserver
{

    /**
     * @var Indicator[]
     */
    private $indicators;
    //TODO: Индикаторы могут быть как на вход в позицию так и на выход из нее
    // надо бы сделать чтобы индикаторы разделялись на группы. это касается и метода
    // checkIndicators()

    private $state;
    private $mainSymbol;
    private $quoteSymbol;

    public function __construct($exchangeName, $accountEmail, $mainSymbol, $quoteSymbol)
    {
        parent::__construct($exchangeName, $accountEmail);
        $this->state = false;
        $indicators = $this->loadIndicators();
        $this->initializeIndicators($indicators);
        var_dump($this->account->getBalance());
        $this->mainSymbol = $mainSymbol;
        $this->quoteSymbol = $quoteSymbol;
    }

    public function update(SplSubject $subject)
    {
        printf(
            'Индикатор [%s] изменил состояние и вызвал метод [update] стратегии [%s]. %s',
            get_class($subject),
            self::class,
            PHP_EOL
        );
        if($this->checkIndicators() === true){
            printf('Пытаюсь купить валюту. %', PHP_EOL);
            //TODO: Продумать процесс входа в позицию
            // ( может частично откупиться/отмениться в случае с limit )
            // ,а покачто можно сделать только market
        }
    }

    private function initializeIndicators(array $indicatorNames = [])
    {
        foreach ($indicatorNames as $indicatorName){
            $indicatorClass = sprintf(
                '\BBot\Indicators\%sIndicator'
                ,ucfirst($indicatorName)
            );
            $endpoint = sprintf(
                'ipc://%s-%s-%s-%s.ipc',
                lcfirst($this->getExchangeName()),
                $this->mainSymbol,
                $this->quoteSymbol,
                'bookTicker'
            );
            $this->indicators[$indicatorClass] = new $indicatorClass($endpoint);
            end($this->indicators)->attach($this);
        }
    }

    private function checkIndicators(): bool
    {
        $newState = true;

        foreach ($this->indicators as $indicator){
            if($indicator->getState() === false){
                $newState = false;
                break;
            }
        }

        $isStateChanged = $newState !== $this->state;

        return $isStateChanged;
    }

    private function loadIndicators()
    {
        $accounts = Yaml::parseFile( __DIR__ . '/../../accounts.yaml');
        $account = $accounts[lcfirst($this->getExchangeName())]['emails'][$this->account->getName()];
        $strategies = $account['strategies'];
        var_dump($this->getStrategyName());
        $currentStrategy = $strategies[$this->getStrategyName()];
        return $currentStrategy['indicators'];
    }
}