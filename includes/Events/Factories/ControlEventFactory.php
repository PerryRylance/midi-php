<?php

namespace PerryRylance\Midi\Events\Factories;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;

class ControlEventFactory
{
    public static function fromStream(ReadStream $stream, int $leading, int $delta, StatusBytes $status): ControlEvent
    {
        $running = ($leading & 0xF0) < 0x80;

        if($running)
        {
            $type = $status[0];
            $channel = $status[1];

            $stream->seekRelative(-1);
        }
        else
        {
            $status[0] = $type = $leading & 0xF0;
            $status[1] = $channel = $leading & 0x0F;
        }

        switch($type)
        {
            default:
                throw new ParseException("Invalid control event type 0x" . dechex($type));
        }

        $result->readBytes($stream);

        return $result;
    }
}
