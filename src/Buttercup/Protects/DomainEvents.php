<?php

namespace Buttercup\Protects;

interface DomainEvents
{
    /**
     * @return DomainEvent[]
     */
    public function getAll();
}