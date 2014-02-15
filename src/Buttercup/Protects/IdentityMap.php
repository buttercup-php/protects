<?php

namespace Buttercup\Protects;

/**
 * Holds unique Aggregate instances in memory, mapped by id. Useful to make sure an Aggregate is not loaded twice.
 */
interface IdentityMap
{
    /**
     * @param TracksChanges $aggregate
     * @throws MultipleInstancesOfAggregateDetected
     * @return void
     */
    public function attach(TracksChanges $aggregate);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksChanges | null
     */
    public function find(IdentifiesAggregate $aggregateId);
}