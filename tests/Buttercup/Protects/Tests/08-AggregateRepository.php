<?php
// As we will have multiple `Basket` instances in our system, we'll want to collect them in an AggregateRepository.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\AggregateRoot;
use Buttercup\Protects\AggregateRepository;
use Buttercup\Protects\IdentifiesAggregate;
use Buttercup\Protects\RecordsEvents;
use Buttercup\Protects\Tests\Misc\ProductId;

final class BasketRepository implements AggregateRepository
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param RecordsEvents $aggregate
     * @return void
     */
    public function add(RecordsEvents $aggregate)
    {
        // To store the updates made to an Aggregate, we only need to commit the latest recorded events to the `EventStore`.
        $events = $aggregate->getRecordedEvents();
        $this->eventStore->commit($events);
        $aggregate->clearRecordedEvents();
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return Basket
     */
    public function get(IdentifiesAggregate $aggregateId)
    {
        // Fetching a single `Basket` is extremely easy: all we need is to reconstitute it from its history! Compare
        // that to the complexity of traditional ORMs.
        $aggregateHistory = $this->eventStore->getAggregateHistoryFor($aggregateId);
        return Basketv4::reconstituteFrom($aggregateHistory);
    }
}


$basketId = BasketId::generate();
$basket = BasketV4::pickUp($basketId);
$basket->addProduct(ProductId::fromString('TPB01'), "The Princess Bride");

$baskets = new BasketRepository(new InMemoryEventStore());
$baskets->add($basket);
$reconstitutedBasket = $baskets->get($basketId);

it('should reconstitute a Basket to its state after persisting it',
    $basket == $reconstitutedBasket
);
