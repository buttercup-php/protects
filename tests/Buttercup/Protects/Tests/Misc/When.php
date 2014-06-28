<?php

namespace Buttercup\Protects\Tests\Misc;

use Buttercup\Protects\DomainEvent;
use Verraes\ClassFunctions\ClassFunctions;

trait When
{
    protected function when(DomainEvent $event)
    {
        $method = 'when' . ClassFunctions::short($event);
        $this->$method($event);
    }
}