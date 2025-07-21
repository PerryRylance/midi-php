<?php

namespace PerryRylance\Midi;

use Linna\TypedArrayObject\ArrayOfClasses;
use PerryRylance\Midi\Collections\EventCollection;
use PerryRylance\Midi\Events\Control\ControlEvent;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Exceptions\UnsupportedTrackException;
use PerryRylance\Midi\Streams\ReadStream;
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

    public function readBytes(ReadStream $stream): void
    {
        if($stream->readUint() !== Track::HEADER_CHUNK_ID)
            throw new UnsupportedTrackException('Expected MTrk, only MIDI trakcs are supported presently');

        $chunkSize = $stream->readUint();
        $status = new StatusBytes();

        $bytes = $cursor = 0;
        $eot = false;

        /** @var Event $event */
        $event = null;

        while($bytes < $chunkSize)
        {
            if($eot)
                throw new ParseException('Unexpected end of track event');

            $cursor = $stream->getPosition();

            $delta = $stream->readVlv();

            $event = EventFactory::fromStream($stream, $status, $delta);

            if(!($event instanceof ControlEvent))
                $status[0] = $status[1] = 0; // NB: Not a control event, reset status bytes

            if($event instanceof EndOfTrackEvent)
                $eot = true;

            $this->events->append($event);

            $bytes += $stream->getPosition() - $cursor;
        }

        if(!$eot)
            throw new ParseException('Expected end of track event');

        if($bytes < $chunkSize)
            throw new ParseException('Expected bytes read to be equal to specified chunk size');
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

        $trackEndPosition = $stream->getPosition();
        $chunkSize = $trackEndPosition - $chunkSizePosition - 4; // NB: Delta bytes minus the 4 bytes for the chunk uint itself

        $validator->assertValidSize($chunkSize);

        $stream->seekTo($chunkSizePosition);
        $stream->writeUint($chunkSize);

        $stream->seekTo($trackEndPosition);
    }
}
