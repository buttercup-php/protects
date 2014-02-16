<?php

namespace Buttercup\Protects\Tests\Misc;

use Buttercup\Protects\IdentifiesAggregate;

final class ProductId implements IdentifiesAggregate
{
    private $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public static function generate()
    {
        // don't do this in production code, use something like https://github.com/ramsey/uuid
        return new ProductId(md5(uniqid()));
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