<?php

namespace Buttercup\Protects;

/**
 * An ordered list of events that represents the complete history of a single Aggregate's instance.
 */
interface AggregateHistory extends DomainEvents
{
    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId();
}
