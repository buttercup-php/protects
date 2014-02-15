<?php

namespace Buttercup\Protects;


/**
 * Something that happened in the past, that is of importance to the business.
 */
interface DomainEvent
{
    /**
     * The Aggregate this event belongs to.
     * @return IdentifiesAggregate
     */
    public function getAggregateId();

    /**
     * The version that the Aggregate was at the time this event was raised.
     * @return int
     */
    public function aggregateVersion();
}