<?php

namespace Buttercup\Protects;

/**
 * A collection-oriented Repository for eventsourced Aggregates.
 */
interface AggregateRepository
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @return IsEventSourced
     */
    public function get(IdentifiesAggregate $aggregateId);

    /**
     * @param RecordsEvents $aggregate
     * @return void
     */
    public function add(RecordsEvents $aggregate);
}