<?php

namespace PerryRylance\Midi\Events\Factories;

use LogicException;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\EventType;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;

class EventFactory
{
    /**
     * @template TEvent of Event
     * @param ReadStream $stream
     * @param StatusBytes $status
     * @return TEvent
     */
    public static function fromStream(ReadStream $stream, StatusBytes $status, ?int $delta = 0): Event
    {
        $type = $stream->readByte();
        
        switch($type)
        {
            case EventType::META:
                throw new LogicException("Not yet implemented");

            case EventType::SYSEX:
                throw new LogicException("Not yet implemented");
            
            default:
                throw new LogicException("Not yet implemented");
        }

        return $result;
    }
}
