<?php
// A full-blown **EventStore** is out of scope for Buttercup.Protects. Here you can find a very simple `InMemoryEventStore`
// to help us to proceed to the next part. The interface consists of a `commit(DomainEvents)` and a
// `getAggregateHistoryFor($id)` method.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\AggregateHistory;
use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\DomainEvents;
use Buttercup\Protects\IdentifiesAggregate;

final class InMemoryEventStore
{
    // We will simply store all the `DomainEvents` chronologically in an array, instead of persisting them in a database.
    private $events = [];

    // A commit is always transactional. Either the whole set of `DomainEvents` is persisted, or none of them are.
    // Usually, a commit will consist of events from a single operation on a single `Aggregate`.
    public function commit(DomainEvents $events)
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    // The only read operation we support for now, is fetching all the events that make up the complete history of a
    // single Aggregate instance, identified by an id.
    /**
     * @return AggregateHistory
     */
    public function getAggregateHistoryFor(IdentifiesAggregate $id)
    {
        return new AggregateHistory(
            $id,
            array_filter(
                $this->events,
                function (DomainEvent $event) use ($id) { return $event->getAggregateId()->equals($id); }
            ));
    }
} 