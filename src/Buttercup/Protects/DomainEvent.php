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
}