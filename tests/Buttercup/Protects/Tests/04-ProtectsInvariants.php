<?php
// **An invariant is a statement about the domain that holds true, no matter what**. By enforcing invariants, we ensure
// **consistency** in the domain model. This allows us to write code in all the layers that surround our model, without
// having to worry about this consistency. Arguably, protecting invariants is one of the most important aspects of
// building models, which is of course why this library is called *Buttercup.Protects*. But, ironically, the library
// doesn't offer much help to actually protect invariants. Most likely, this is because invariants are very specific to
// a articular domain anyway.
namespace Buttercup\Protects\Tests;

use Buttercup\Protects\Tests\Misc\ProductId;
use Exception;

require_once __DIR__ . '/../../../../vendor/autoload.php';

// As an example, let's introduce an invariant that states that *"A Basket can have no more than three Products"*.
// Let's write a test that proves that the a `BasketLimitReached` exception is thrown when we try to violate the invariant.
$test = function() {
    $basket = BasketV2::create(BasketId::generate());
    $basket->addProduct(new ProductId(1));
    $basket->addProduct(new ProductId(2));
    $basket->addProduct(new ProductId(3));
    it("should disallow adding a fourth product",
        throws('Buttercup\Protects\Tests\BasketLimitReached', function () use($basket) {
            $basket->addProduct(new ProductId(4));
        })
    );
};

// Here's the simplest possible implementation of this invariant. We're ignoring everything we had in the previous
// chapter about `RecordsEvents`, for the sake of the example, just to focus on the invariant here.
final class BasketV2
{
    private $products = [];

    public function addProduct(ProductId $productId)
    {
        // A guard protects the invariant.
        $this->guardProductLimit();
        // If no exception was thrown, we keep a list of products.
        $this->products[] = $productId;
    }

    // We could have inlined this code, but having it in a separate method, makes the code more readable and clearly
    // states the purpose.
    private function guardProductLimit()
    {
        if (count($this->products) >= 3) {
            throw new BasketLimitReached;
        }
    }

    // Some code from the previous chapter, but of course you already know this stuff.
    /**
     * @var BasketId
     */
    private $basketId;

    private function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    public static function create(BasketId $basketId)
    {
        return new BasketV2($basketId);
    }

}
// I like to have very specific exceptions for each possible invariant.
final class BasketLimitReached extends Exception {}

$test();