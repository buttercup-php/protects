<?php

namespace Buttercup\Protects;

/**
 * An object that identifies an Aggregate. Typically a UUID, but any kind of id will do, as long as it is unique within
 * the system.
 */
interface IdentifiesAggregate
{
    /**
     * @param $string
     * @return IdentifiesAggregate
     */
    public static function fromString($string);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param $other
     * @return boolean
     */
    public function equals(IdentifiesAggregate $other);
}
 