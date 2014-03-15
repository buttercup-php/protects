<?php

namespace Buttercup\Protects;

/**
 * A collection-oriented Repository for eventsourced Aggregates.
 */
interface AggregateRepository
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateRoot
     */
    public function get(IdentifiesAggregate $aggregateId);

    /**
     * @param AggregateRoot $aggregate
     * @return void
     */
    public function add(AggregateRoot $aggregate);
}