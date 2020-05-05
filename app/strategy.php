<?php

use BBot\Strategy\TestStrategy;

require __DIR__ . '/vendor/autoload.php';

foreach ($argv as $arg) {
    [$key, $value] = explode('=',$arg);
    $$key = $value;
}
$options = [
    ''
];
$strategyName = ucfirst($strategyName);
$strategyClass = sprintf('BBot\Strategy\%sStrategy',$strategyName);

//TODO: Заменить на интерфейс стратегии
/**
 * @var TestStrategy
 */
$strategy = new $strategyClass($options);