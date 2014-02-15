<?php

namespace Buttercup\Protects;

/**
 * A unit of work that acts both as an identity map and a change tracker.  It does not commit/save/persist. Its role is
 * reduced to tracking multiple aggregates, and to hand you back those that have changed. Persisting the ones that have
 * changed, happens on the outside.
 */
interface UnitOfWork extends IdentityMap
{
    /**
     * Returns AggregateRoots that have changed.
     * @return TracksChanges[]
     */
    public function getChanges();
}
 