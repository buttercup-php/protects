<?php
// Domain Events describe things that have happened, but where do they come from? Wouldn't it be nice if we could make
// the objects themselves responsible for recording whatever happened to them? We can, by implementing the
// `RecordsEvents` interface.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\DomainEvents;
use Buttercup\Protects\RecordsEvents;
use Buttercup\Protects\Tests\Misc\ProductId;

// Being good TDD'ers, let's write our tests first.
$test = function() {
    // We pick up a Basket, add a product, and remove the product again.
    $basket = Basket::pickUp(BasketId::generate());
    $basket->addProduct(new ProductId('TPB123'), "The Princess Bride");
    $basket->removeProduct(new ProductId('TPB123'));

    // We'll want the recorded events to reflect that these three operations have happened.
    $events = $basket->getRecordedEvents();
    it("should have recorded 3 events",
        3 == count($events));
    it("should have a BasketWasPickedUp event",
        $events[0] instanceof BasketWasPickedUp);
    it("should have a ProductWasAddedToBasket event",
        $events[1] instanceof ProductWasAddedToBasket);
    it("should have a ProductWasRemovedFromBasket event",
        $events[2] instanceof ProductWasRemovedFromBasket);

    // We'll want a way to clear the events, so that the next time we call a method on Basket, we don't get a list with
    // old and new events mixed in the same result.
    $basket->clearRecordedEvents();
    it("should have no more events after clearing it",
        0 == count($basket->getRecordedEvents()));
};

// We'll implement the simplest Basket we can to satisfy the tests. By ignoring the PHP syntax, you can read the class
// definition as **natural language**:
// > A basket records events.
final class Basket implements RecordsEvents
{
    /**
     * @var BasketId
     */
    private $basketId;

    // The **constructor is private**. Currently the only way to instantiate it, is by using `pickUp()`.
    // We'll add another way later.
    private function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    // A simple little static factory. Note that it records an event as well.
    public static function pickUp(BasketId $basketId)
    {
        $basket = new Basket($basketId);
        // Again, this reads very naturally:
        // > Record that a basket was picked up.
        $basket->recordThat(
            new BasketWasPickedUp($basketId)
        );
        return $basket;
    }

    // The `addProduct()` and `removeProduct()` methods are the two other operations that we identified in our domain.
    public function addProduct(ProductId $productId, $name)
    {
       $this->recordThat(
           new ProductWasAddedToBasket($this->basketId, $productId, $name)
       );
    }

    public function removeProduct(ProductId $productId)
    {
       $this->recordThat(
           new ProductWasRemovedFromBasket($this->basketId, $productId)
       );
    }

    // We've called the `recordThat()` method a couple of times, so now is a good time to implement it. It's not part of
    // the `RecordsEvents` interface. As with pretty much everything in Buttercup.Protects, you can **choose for yourself
    // how you do this and where you put this**. For example, this method and the ones below, could be part of a trait or
    // an abstract parent somewhere.
    private function recordThat(DomainEvent $domainEvent)
    {
        $this->latestRecordedEvents[] = $domainEvent;
    }

    // We only keep a list of newly recorded events since the last `clearRecordedEvents()`. If we wanted to, we could
    // also keep a separate list of *all* events, but `RecordsEvents` doesn't require it. (I define the property here,
    // instead of at the top of the class, because it fits the narrative of this documentation better.)
    /**
     * @var DomainEvent[]
     */
    private $latestRecordedEvents = [];

     // Finally, we implement `RecordsEvents`. We get all the Domain Events that were recorded since the last time it
     // was cleared, or since it was restored from persistence.
    /**
     * @return DomainEvents
     */
    public function getRecordedEvents()
    {
        // `DomainEvents` is an `ImmutableArray` of `DomainEvent` objects.
        return new DomainEvents($this->latestRecordedEvents);
    }

    // Clears the record of new Domain Events.
    public function clearRecordedEvents()
    {
        $this->latestRecordedEvents = [];
    }
}

// Run the tests
$test();