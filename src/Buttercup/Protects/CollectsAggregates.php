<?php

namespace Buttercup\Protects;

/**
 * A repository for Aggregates
 */
interface CollectsAggregates
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateRoot
     */
    public function get(IdentifiesAggregate $aggregateId);
}