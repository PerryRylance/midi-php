<?php

namespace PerryRylance\Midi\Events\Factories;

use PerryRylance\Midi\Events\Meta\MetaEvent;
use PerryRylance\Midi\Events\Meta\MetaEventType;
use PerryRylance\Midi\Events\Meta\TextEvent;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

class MetaEventFactory
{
    public static function fromStream(ReadStream $stream, int $delta): MetaEvent
    {
        $type = MetaEventType::tryFrom($stream->readByte());

        switch($type)
        {
            case MetaEventType::TEXT:
                $result = new TextEvent();
                break;
            
            default:
                throw new ParseException("Invalid meta event type 0x" . dechex($type->value));
        }

        $result->readBytes($stream);

        return $result;
    }
}
