<?php
// **An invariant is a statement about the domain that holds true, no matter what**. By enforcing invariants, we ensure
// **consistency** in the domain model. This allows us to write code in all the layers that surround our model, without
// having to worry about this consistency. Arguably, protecting invariants is one of the most important aspects of
// building models, which is of course why this library is called *Buttercup.Protects*. But, ironically, the library
// doesn't offer much help to actually protect invariants. Most likely, this is because invariants are very specific to
// a particular domain anyway.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\DomainEvents;
use Buttercup\Protects\RecordsEvents;
use Buttercup\Protects\Tests\Misc\ProductId;
use Exception;

// As an example, let's introduce an invariant that states that *"A Basket can have no more than three Products"*.
// Let's write a test that proves that the a `BasketLimitReached` exception is thrown when we try to violate the invariant.
$test = function() {
    $basket = BasketV2::pickUp(BasketId::generate());
    $basket->addProduct(new ProductId('TPB1'), "The Princess Bride");
    $basket->addProduct(new ProductId('TPB2'), "The book");
    $basket->addProduct(new ProductId('TPB3'), "The DVD");
    it("should disallow adding a fourth product",
        throws('Buttercup\Protects\Tests\BasketLimitReached', function () use($basket) {
            $basket->addProduct(new ProductId('TPB4'), "The video game");
        })
    );
};

// (The V2 is just to prevent naming collisions, and can be ignored.)
final class BasketV2 implements RecordsEvents
{
    private $productCount;

    public static function pickUp(BasketId $basketId)
    {
        $basket = new BasketV2($basketId);
        $basket->recordThat(
            new BasketWasPickedUp($basketId)
        );
        // A new basket doesn't have any products yet. It's good to set the initial state explicitly here.
        $basket->productCount = 0;
        return $basket;
    }

    public function addProduct(ProductId $productId, $name)
    {
        // A guard protects the invariant, and throws if it is violated
        $this->guardProductLimit();
        // If no exception was thrown, we record the event, and increment the productCount.
        $this->recordThat(
            new ProductWasAddedToBasket($this->basketId, $productId, $name)
        );
        ++$this->productCount;
    }

    // We could have inlined this, but having it in a separate method, makes the code more readable and clearly
    // states the purpose.
    private function guardProductLimit()
    {
        if ($this->productCount >= 3) {
            throw new BasketLimitReached;
        }
    }

    public function removeProduct(ProductId $productId)
    {
        $this->recordThat(
            new ProductWasRemovedFromBasket($this->basketId, $productId)
        );
        // And of course we decrement the count again when removing a Product.
        --$this->productCount;
    }

    // The rest of the code is the same as the previous chapter.
    private $basketId;
    private $latestRecordedEvents = [];
    private function __construct(BasketId $basketId) { $this->basketId = $basketId; }
    private function recordThat(DomainEvent $domainEvent) { $this->latestRecordedEvents[] = $domainEvent; }
    public function getRecordedEvents() { return new DomainEvents($this->latestRecordedEvents); }
    public function clearRecordedEvents() { $this->latestRecordedEvents = []; }
}

// I like to have very specific exceptions for each possible invariant.
final class BasketLimitReached extends Exception {}

$test();
