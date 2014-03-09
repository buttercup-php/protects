<?php
namespace Buttercup\Protects;

use ArrayAccess;
use Countable;
use Iterator;
use SplFixedArray;

abstract class ImmutableArray extends SplFixedArray implements Countable, Iterator, ArrayAccess
{
    public function __construct(array $items)
    {
        parent::__construct(count($items));
        $i = 0;
        foreach($items as $item) {
            $this->guardType($item);
            parent::offsetSet($i++, $item);
        }
    }

    /**
     * Throw when the item is not an instance of the accepted type.
     * @throws \InvalidArgumentException
     * @param $item
     * @return void
     */
    abstract protected function guardType($item);

    final public function count()
    {
        return parent::count();
    }

    final public function current()
    {
        return parent::current();
    }

    final public function key()
    {
        return parent::key();
    }

    final public function next()
    {
        parent::next();
    }

    final public function rewind()
    {
        parent::rewind();
    }

    final public function valid()
    {
        return parent::valid();
    }

    final public function offsetExists($offset)
    {
        return parent::offsetExists($offset);
    }

    final public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }

    final public function offsetSet($offset, $value)
    {
        throw new ArrayIsImmutable();
    }

    final public function offsetUnset($offset)
    {
        throw new ArrayIsImmutable();
    }

}