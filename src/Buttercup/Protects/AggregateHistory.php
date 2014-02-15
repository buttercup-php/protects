<?php

namespace Buttercup\Protects;

/**
 * Represents the complete history of an Aggregate instance
 */
interface AggregateHistory extends DomainEvents
{
    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId();
}