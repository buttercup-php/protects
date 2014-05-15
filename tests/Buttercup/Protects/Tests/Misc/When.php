<?php

namespace Buttercup\Protects\Tests\Misc;

use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\DomainEvents;
use Verraes\ClassFunctions\ClassFunctions;

trait When
{
    use \Buttercup\Protects\When;

    protected function when(DomainEvent $event)
    {
        $method = 'when' . ClassFunctions::short($event);
        $this->$method($event);
    }

    protected function whenAll(DomainEvents $events)
    {
        foreach($events as $event) {
            $this->when($event);
        }
    }
}