<?php

namespace Buttercup\Protects\Tests\Misc;

use Buttercup\Protects\IdentifiesAggregate;

final class ProductId implements IdentifiesAggregate
{
    /**
     * @var string
     */
    private $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public static function fromString($string)
    {
        return new ProductId($string);
    }

    public function __toString()
    {
        return $this->productId;
    }

    public function equals(IdentifiesAggregate $other)
    {
        return
            $other instanceof ProductId
            && $this->productId == $other->productId;
    }
} 