<?php

namespace Buttercup\Protects;

/**
 * An logical, ordered set of DomainEvent instances that belong together for one reason or another.
 */
interface DomainEvents
{
    /**
     * @return DomainEvent
     */
    public function next();
}
 