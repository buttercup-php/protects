<?php

namespace Buttercup\Protects;

interface AggregateRoot extends RecordsEvents, IsEventSourced, TracksChanges
{

} 