<?php


namespace BBot\Indicators;


use BBot\CollectableDataInterface;

interface IndicatorObserverInterface
{
    public function update(CollectableDataInterface $collectableData);
}