<?php

namespace Buttercup\Protects;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * An ordered list of DomainEvent objects
 */
interface DomainEvents extends Countable, Iterator, ArrayAccess
{

}

// @todo move
class DomainEventsIMPL extends ImmutableArray
{
    /**
     * Throw when the type of item is not accepted.
     *
     * @param $item
     * @throws ArrayIsImmutable
     * @return void
     */
    protected function guardType($item)
    {
        if(!($item instanceof DomainEvent)) {
            throw new ArrayIsImmutable;
        }
    }
}