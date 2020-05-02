<?php


namespace BBot\Indicators;

use BBot\CanSubscribeToZMQ;
use SplSubject;

interface Indicator extends CanSubscribeToZMQ, SplSubject
{
    public function check($dataItem);
}