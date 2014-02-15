<?php

namespace Buttercup\Protects;

/**
 * React to a set of DomainEvents
 */
interface AcceptsEvents
{
    /**
     * @param DomainEvents $events
     * @return void
     */
    public function when(DomainEvents $events);
}
 