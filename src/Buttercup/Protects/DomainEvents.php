<?php

namespace Buttercup\Protects;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * An ordered list of DomainEvent objects
 */
interface DomainEvents extends Countable, Iterator, ArrayAccess
{

}
