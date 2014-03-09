<?php

namespace Buttercup\Protects;

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