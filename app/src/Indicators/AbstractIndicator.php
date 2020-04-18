<?php


namespace BBbot\Indicators;


use BBot\AbstractPublisher;
use BBot\Indicators\IndicatorInterface;
use Exception;
use SplObjectStorage;
use SplObserver;
use SplSubject;

abstract class AbstractIndicator extends AbstractPublisher implements IndicatorInterface
{
    private $status = self::STATUS_DEACTIVE;


    /**
     * Возвращает true если условие индикатора положительно.
     * @return bool
     */
    public function isActive():bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @param string $newStatus
     * @throws Exception
     */
    public function setStatus(string $newStatus)
    {
        switch ($newStatus){
            case self::STATUS_ACTIVE:
                $this->status = self::STATUS_ACTIVE;
                break;
            case self::STATUS_DEACTIVE:
                $this->status = self::STATUS_DEACTIVE;
                break;
            default:
                throw new Exception('Undefined status');
        }
    }

}