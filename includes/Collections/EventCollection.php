<?php

namespace PerryRylance\Midi\Collections;

use Aeviiq\Collection\ObjectCollection;
use PerryRylance\Midi\Events\Event;

final class EventCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return Event::class;
    }
}
