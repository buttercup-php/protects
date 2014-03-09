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

/**
 * @todo move
 */
final class AggregateHistoryIMPL extends DomainEventsIMPL implements AggregateHistory
{
    public function __construct(IdentifiesAggregate $aggregateId, array $events)
    {
        /** @var $event DomainEvent */
        foreach($events as $event) {
            if(!$event->getAggregateId()->equals($aggregateId)) {
                throw new CorruptAggregateHistory;
            }
        }
        parent::__construct($events);
    }


    /**
     * @return IdentifiesAggregate
     */
    public function getAggregateId()
    {

    }
}