<?php


namespace BBot;


use SplObjectStorage;
use SplObserver;
use SplSubject;

abstract class AbstractPublisher implements SplSubject
{
    private $subscribers;

    public function __construct()
    {
        $this->subscribers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer)
    {
        $this->subscribers->attach($observer);
    }

    public function detach(SplObserver $observer)
    {
        $this->subscribers->detach($observer);
    }

    public function notify()
    {
        foreach ($this->subscribers as $subscriber){
            $subscriber->update($this);
        }
    }

}