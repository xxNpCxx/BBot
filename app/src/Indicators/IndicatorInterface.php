<?php


namespace BBot\Indicators;

use SplObserver;
use SplSubject;

/**
 * Является одновременно и издателем и подписчиком
 * так как может меняться в зависимости от одного или группы
 * внешних факторов в тоже время должен оповещать о том что все
 * факторы подтверждены.
 *
 * Интерфейс обсервера кастомный так как не нужно передавать целый обьект
 * и достаточно данных интересующих индикатор.
 */
interface IndicatorInterface extends SplSubject, IndicatorObserverInterface
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DEACTIVE = 'deactive';
}