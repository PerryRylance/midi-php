<?php

namespace PerryRylance\Midi;

use Linna\TypedArrayObject\ArrayOfClasses;
use PerryRylance\Midi\Collections\EventCollection;
use PerryRylance\Midi\Events\Control\ControlEvent;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Validators\TrackValidator;

class Track
{
    const HEADER_CHUNK_ID = 0x4D54726B;

    public EventCollection $events;

    public function __construct()
    {
        $this->events = new EventCollection();
    }

    public function writeBytes(WriteStream $stream): void
    {
        $validator = new TrackValidator($this);

        $stream->writeUint(Track::HEADER_CHUNK_ID);

        $chunkSizePosition = $stream->getPosition();

        $stream->writeUint(0); // NB: Temporarily write zero for chunk size, we'll alter this later

        $status = new StatusBytes();

        for($i = 0; $i < $this->events->count(); $i++)
        {
            $event = $this->events[$i];

            $validator->validateEvent($event, $i);

            // NB: Delta written here. Delta is a track concept and not related to pure events. We do this here so that events can be streamed in real time.
            $stream->writeVlv($event->delta);

            $event->writeBytes($stream, $status);

            if(!($event instanceof ControlEvent))
                $status[0] = $status[1] = 0; // NB: Reset status bytes
        }

        // TODO: Write chunk size etc.
    }
}
