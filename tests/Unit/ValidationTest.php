<?php

use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Track;

function getCMajorEvents(): array
{
    $events = [];
    $pitches = [60, 62, 64, 65, 67, 69, 71, 72];
    $delta = 480;

    foreach($pitches as $pitch)
    {
        $event = new NoteOnEvent($delta);
        $event->pitch = $pitch;

        $events []= $event;
    }

    return $events;
}

it('fails validation on premature end of track event', function() {

    $track = new Track();
    $stream = new WriteStream();

    $track->events[0] = new EndOfTrackEvent();
    $track->events->merge(getCMajorEvents());

    $track->writeBytes($stream);

})
    ->throws(ValidationException::class);
