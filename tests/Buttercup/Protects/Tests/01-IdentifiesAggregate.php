<?php
// We use a **Value Object** to identify an **Aggregate**. This prevents the sort of bugs where id's got mixed up, and is a nice
// and explicit way of dealing with id's anyway. You can use any kind of id's, as long as they
// implement `IdentifiesAggregate`. **UUIDs** are recommended, because they are universally unique, and they can be generated
// in the client. Some infrastructure, such as an **Event Store**, might require UUIDs exclusively, so they can optimize
// for it.
namespace Buttercup\Protects\Tests;
use Buttercup\Protects\IdentifiesAggregate;

// Simply use the `IdentifiesAggregate` interface on a class such as BasketId, and implement its methods.
final class BasketId implements IdentifiesAggregate
{
    private $basketId;
    // You are free to extend from an abstract class, and to implement the constructor as you wish. For example, you
    // could add some validation in there.
    /**
     * @param string $basketId
     */
    public function __construct($basketId)
    {
        $this->basketId = (string) $basketId;
    }

    public static function fromString($string)
    {
        return new BasketId($string);
    }

    public function __toString()
    {
        return $this->basketId;
    }

    public function equals(IdentifiesAggregate $other)
    {
        return
            $other instanceof BasketId
            && $this->basketId == $other->basketId;
    }

    // A nice convention is to have a static generate() method to create a random BasketId. The example here is a bad
    // way to generate a UUID, so don't do this in production. Use something like https://github.com/ramsey/uuid
    public static function generate()
    {
        $badSampleUuid = md5(uniqid());
        return new BasketId($badSampleUuid);
    }
}

// Sample usage:
$basketId = BasketId::fromString('12345678-90ab-cdef-1234-567890abcedf1234');
// Casting to string gives you the original string back:
it("should cast to string",
    (string) $basketId == '12345678-90ab-cdef-1234-567890abcedf1234');
// Testing equality:
it("should equal instances with the same type and value",
    (new BasketId('same'))->equals(new BasketId('same')));
it("should not equal instances with a different value",
    !(new BasketId('other'))->equals(new BasketId('same')));