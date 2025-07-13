<?php

namespace PerryRylance\Midi\Events\Factories;

use LogicException;
use PerryRylance\Midi\Events\Control\ControlEvent;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\EventType;
use PerryRylance\Midi\Events\SysEx\SysExEvent;
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
            case EventType::META->value:
                $result = MetaEventFactory::fromStream($stream, $delta);
                break;

            case EventType::SYSEX->value:
                $result = new SysExEvent($delta);
                $result->readBytes($stream);
                break;

            default:
                $result = ControlEventFactory::fromStream($stream, $type, $delta, $status);
                break;
        }

        return $result;
    }
}
