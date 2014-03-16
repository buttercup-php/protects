<?php
// Before coding your **Domain Model**, discover the **Domain Events** that you need. A Domain Event is something that
// happened in the past, and that is of interest to the business. They are first-class citizens of your model, and in an
// **Event Sourced** system, they are the most important elements. So work closely with your **Domain Expert** to figure
// out what is and is not important to the business.
namespace Buttercup\Protects\Tests;
use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\Tests\Misc\ProductId;

// For the sake of the example, we imagine a simple e-commerce system. We're very interested in what people put in their
// shopping basket, and what they take out. Because every shopping session starts with a new basket, we're interested in
// that event as well.
final class BasketWasPickedUp implements DomainEvent
{
    private $basketId;

    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    public function getAggregateId()
    {
        return $this->basketId;
    }
}

// You've already noticed some interesting concepts:
// - The name of the event is in the past tense. `BasketWasPickedUp` fits nicely into our **Ubiquitous Language**. We
// could have called it something bland like `BasketWasCreated`, but I want to illustrate the use of language.
// `BasketPickedUp`, without the 'was', would've worked as well. I prefer to make sentences though.
// - We implement `DomainEvent`.
// - The `getAggregateId()` method returns `IdentifiesAggregate`. We'll get to **Aggregates** soon.
final class ProductWasAddedToBasket implements DomainEvent
{
    private $basketId;
    private $productId;
    private $productName;

    // Notice how all the properties go in the constructor.
    public function __construct(BasketId $basketId, ProductId $productId, $productName)
    {
        $this->basketId = $basketId;
        $this->productName = $productName;
        $this->productId = $productId;
    }

    public function getAggregateId()
    {
        return $this->basketId;
    }

    // We only deal with `ProductId`. We haven't got a Product, strictly speaking we don't even known whether we'll ever
    // need it, and what it would look like. All we know is that in this context, we're only interested in the id...
    public function getProductId()
    {
        return $this->productId;
    }

    // ... and in the name. We'll get to that.
    public function getProductName()
    {
        return $this->productName;
    }
}

// Notice that Domain Events are **immutable**. Once they have been initialized, there's no way to change them, there
// are No setters. That makes perfect sense: History can't be altered!
final class ProductWasRemovedFromBasket implements DomainEvent
{
    private $basketId;
    private $productId;

    public function __construct(BasketId $basketId, ProductId $productId)
    {
        $this->basketId = $basketId;
        $this->productId = $productId;
    }

    public function getAggregateId()
    {
        return $this->basketId;
    }

    public function getProductId()
    {
        return $this->productId;
    }
}

// Let's test one of our Domain Events. Did we mention this documentation doubles as test suite?
$event = new ProductWasAddedToBasket(new BasketId('BAS1'), new ProductId('PRO1'), "The Princess Bride");
it('should equal another instance with the same value',
    $event->getAggregateId()->equals(new BasketId('BAS1')));
it("should expose a ProductId",
    $event->getProductId()->equals(new ProductId('PRO1')));
it("should expose a productName",
    $event->getProductName() == "The Princess Bride");

