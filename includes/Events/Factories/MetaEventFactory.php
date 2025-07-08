<?php

namespace PerryRylance\Midi\Events\Factories;

use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\CuePointEvent;
use PerryRylance\Midi\Events\Meta\InstrumentNameEvent;
use PerryRylance\Midi\Events\Meta\LyricEvent;
use PerryRylance\Midi\Events\Meta\MarkerEvent;
use PerryRylance\Midi\Events\Meta\MetaEvent;
use PerryRylance\Midi\Events\Meta\MetaEventType;
use PerryRylance\Midi\Events\Meta\SetTempoEvent;
use PerryRylance\Midi\Events\Meta\SmtpeOffsetEvent;
use PerryRylance\Midi\Events\Meta\TextEvent;
use PerryRylance\Midi\Events\Meta\TrackNameEvent;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

class MetaEventFactory
{
    public static function fromStream(ReadStream $stream, int $delta): MetaEvent
    {
        $type = MetaEventType::tryFrom($stream->readByte());

        $result = match($type) {
            MetaEventType::TEXT => new TextEvent,
            MetaEventType::COPYRIGHT => new CopyrightEvent,
            MetaEventType::TRACK_NAME => new TrackNameEvent,
            MetaEventType::INSTRUMENT_NAME => new InstrumentNameEvent,
            MetaEventType::LYRIC => new LyricEvent,
            MetaEventType::MARKER => new MarkerEvent,
            MetaEventType::CUE_POINT => new CuePointEvent,
            MetaEventType::SET_TEMPO => new SetTempoEvent,
            MetaEventType::SMPTE_OFFSET => new SmtpeOffsetEvent,
            default => throw new ParseException("Invalid meta event type 0x" . dechex($type->value))
        };

        $result->readBytes($stream);

        return $result;
    }
}
