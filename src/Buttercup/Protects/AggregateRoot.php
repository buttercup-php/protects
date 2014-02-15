<?php

namespace Buttercup\Protects;

interface AggregateRoot extends ExposesRecordedEvents, IsEventSourced, TracksChanges
{

} 