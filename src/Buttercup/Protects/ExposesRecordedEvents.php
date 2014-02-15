<?php

namespace Buttercup\Protects;

/**
 * An object that can tell an interested party about a set of DomainEvents. Typically used to get the recorded events of
 * an Aggregate, in order to persist or publish them.
 */
interface ExposesRecordedEvents
{
    /**
     * Flushes DomainEvents into the when() method of the argument. This will clear the recorded events.
     */
    public function flushEventsInto(AcceptsEvents $interestedParty);

}