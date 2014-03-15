<?php

namespace Buttercup\Protects;

class DomainEvents extends ImmutableArray
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