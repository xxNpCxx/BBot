<?php


namespace BBot\Indicators;

use BBot\CanSubscribeToZMQ;
use SplSubject;

interface Indicator extends CanSubscribeToZMQ, SplSubject
{
    public function check($dataItem);
    public function isStateChange(bool $state): bool;
    public function getState(): bool;
    public function listen(): void;
}