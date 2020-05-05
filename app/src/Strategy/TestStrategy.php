<?php


namespace BBot\Strategy;

use BBot\Exchange\AbstractClient;
use SplObserver;
use SplSubject;
use function get_class;

/**
 * Тестовая стратегия
 */
class TestStrategy extends AbstractClient implements SplObserver
{

    private $indicators;

    public function __construct($exchangeName, array $indicatorNames = null)
    {
        parent::__construct($exchangeName);
        $this->initializeIndicators($indicatorNames);
    }

    public function update(SplSubject $subject)
    {
        printf(
            'Индикатор [%s] изменил состояние и вызвал метод [update] стратегии %s',
            get_class($subject),
            self::class
        );
    }

    private function initializeIndicators(?array $indicatorNames)
    {

    }
}