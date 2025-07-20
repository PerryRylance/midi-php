<?php

namespace PerryRylance\Midi\Validators;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\Track;

class TrackValidator
{
    public function __construct(private Track $track)
    {
        
    }

    public function validateEvent(Event $event, int $index): void
    {
        if($event instanceof EndOfTrackEvent && $index !== $this->track->events->count() - 1)
            throw new ValidationException('Premature end of track event');
    }
}
