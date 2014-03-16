<?php
// Throwing exceptions is not the only way to protect an invariant. Operations on an Aggregate can have multiple
// different outcomes:
// - An event is recorded. The type and the payload of the event, depend on the state of the Aggregate at the moment.
// - Multiple events are recorded (less common, but it can be useful if you need more granularity.)
// - An exception is thrown.
// - Nothing happens at all. This is a perfectly legal outcome. We'll work through a simple example.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\DomainEvents;
use Buttercup\Protects\RecordsEvents;
use Buttercup\Protects\Tests\Misc\ProductId;

// If we have a Basket with one Product, and we try to remove this Product twice, we could throw an exception. But
// in fact, that would not be necessary in this case. We could simply ignore the second attempt. After all, the
// Basket would still be in a consistent state.
$test = function() {
    $basket = BasketV3::pickUp(BasketId::generate());
    $productId = new ProductId('TPB1');
    $basket->addProduct($productId, "The Princess Bride");
    $basket->removeProduct($productId);
    $basket->removeProduct($productId);
    // `pickUp()`, `addProduct()` and the first `removeProduct()` have resulted in one event each, but the last call
    // to `removeProduct()` has not.
    it("should not record an event when removing a Product that is no longer in the Basket",
        count($basket->getRecordedEvents()) == 3
    );
};

final class BasketV3 implements RecordsEvents
{
    // Note that before, we were not keeping a list of products. An Aggregate has no getters, it represents its state by
    // recording events. Only when we have a good reason, we keep state, such as a `productCount` or a list of `products`.
    /**
     * @var array [productId => count]
     */
    private $products;

    // Strictly speaking, `productCount` is now redundant, as you can calculate it from the `products` array. The point
    // is: we are not trying to describe the state of the Basket. We're keeping very specific bits of state that we need
    // to protect very specific invariants. The state we keep, could take any form; whatever suits our goal.
    private $productCount;

    public function addProduct(ProductId $productId, $name)
    {
        $this->guardProductLimit();
        $this->recordThat(
            new ProductWasAddedToBasket($this->basketId, $productId, $name)
        );

        // We keep a list of each Product and the number of times it was added.
        if(!$this->productIsInBasket($productId)) {
            $this->products[(string) $productId] = 0;
        }

        ++$this->products[(string) $productId];
        // And we still increment `productCount`.
        ++$this->productCount;
    }


    public function removeProduct(ProductId $productId)
    {
        // Now we will only record an event, if the Product was in fact still in the Basket. If it isn't, nothing
        // happens.
        if(! $this->productIsInBasket($productId)) {
            return;
        }

        $this->recordThat(
            new ProductWasRemovedFromBasket($this->basketId, $productId)
        );

        // Update the state.
        --$this->products[(string) $productId];
        --$this->productCount;
    }

    /**
     * @param ProductId $productId
     * @return bool
     */
    private function productIsInBasket(ProductId $productId)
    {
        return
            array_key_exists((string) $productId, $this->products)
            && $this->products[(string)$productId] > 0;
    }

    // The rest of the code is the same as the previous chapter.
    private $basketId;
    private $latestRecordedEvents = [];
    private function guardProductLimit() { if ($this->productCount >= 3) { throw new BasketLimitReached; } }
    public static function pickUp(BasketId $basketId) { $basket = new BasketV3($basketId); $basket->recordThat( new BasketWasPickedUp($basketId) ); $basket->productCount = 0; $basket->products = []; return $basket; }
    private function __construct(BasketId $basketId) { $this->basketId = $basketId; }
    private function recordThat(DomainEvent $domainEvent) { $this->latestRecordedEvents[] = $domainEvent; }
    public function getRecordedEvents() { return new DomainEvents($this->latestRecordedEvents); }
    public function clearRecordedEvents() { $this->latestRecordedEvents = []; }
}

$test();