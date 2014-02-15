<?php

namespace Buttercup\Protects;

/**
 * An AggregateRoot that exposes whether it was changed.
 */
interface TracksChanges
{
    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId();

    /**
     * @return bool
     */
    public function hasChanges();
}
 