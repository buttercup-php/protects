<?php

namespace Buttercup\Protects;

/**
 * An object that identifies an Aggregate. Typically a UUID, but any kind of id will do, as long as it is unique within
 * the system.
 */
interface IdentifiesAggregate
{
    /**
     * Creates an identifier object from a string representation
     * @param $string
     * @return IdentifiesAggregate
     */
    public static function fromString($string);

    /**
     * Returns a string that can be parsed by fromString()
     * @return string
     */
    public function __toString();

    /**
     * Compares the object to another IdentifiesAggregate object. Returns true if both have the same type and value.
     * @param $other
     * @return boolean
     */
    public function equals(IdentifiesAggregate $other);
}
 