<?php

namespace PerryRylance\Midi\Events\Factories;

use PerryRylance\Midi\Events\Meta\ChannelPrefixEvent;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\CuePointEvent;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Events\Meta\InstrumentNameEvent;
use PerryRylance\Midi\Events\Meta\KeySignatureEvent;
use PerryRylance\Midi\Events\Meta\LyricEvent;
use PerryRylance\Midi\Events\Meta\MarkerEvent;
use PerryRylance\Midi\Events\Meta\MetaEvent;
use PerryRylance\Midi\Events\Meta\MetaEventType;
use PerryRylance\Midi\Events\Meta\SequenceNumberEvent;
use PerryRylance\Midi\Events\Meta\SetTempoEvent;
use PerryRylance\Midi\Events\Meta\SmtpeOffsetEvent;
use PerryRylance\Midi\Events\Meta\TextEvent;
use PerryRylance\Midi\Events\Meta\TrackNameEvent;
use PerryRylance\Midi\Events\Meta\PortPrefixEvent;
use PerryRylance\Midi\Events\Meta\SequencerSpecificEvent;
use PerryRylance\Midi\Events\Meta\TimeSignatureEvent;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

class MetaEventFactory
{
    public static function fromStream(ReadStream $stream, int $delta): MetaEvent
    {
        $byte = $stream->readByte();
        $type = MetaEventType::tryFrom($byte);

        $result = match($type) {
            MetaEventType::TEXT => new TextEvent($delta),
            MetaEventType::COPYRIGHT => new CopyrightEvent($delta),
            MetaEventType::TRACK_NAME => new TrackNameEvent($delta),
            MetaEventType::INSTRUMENT_NAME => new InstrumentNameEvent($delta),
            MetaEventType::LYRIC => new LyricEvent($delta),
            MetaEventType::MARKER => new MarkerEvent($delta),
            MetaEventType::CUE_POINT => new CuePointEvent($delta),
            MetaEventType::SET_TEMPO => new SetTempoEvent($delta),
            MetaEventType::SMPTE_OFFSET => new SmtpeOffsetEvent($delta),
            MetaEventType::SEQUENCE_NUMBER => new SequenceNumberEvent($delta),
            MetaEventType::END_OF_TRACK => new EndOfTrackEvent($delta),
            MetaEventType::CHANNEL_PREFIX => new ChannelPrefixEvent($delta),
            MetaEventType::PORT_PREFIX => new PortPrefixEvent($delta),
            MetaEventType::KEY_SIGNATURE => new KeySignatureEvent($delta),
            MetaEventType::TIME_SIGNATURE => new TimeSignatureEvent($delta),
            MetaEventType::SEQUENCER_SPECIFIC => new SequencerSpecificEvent($delta),
            default => throw new ParseException("Invalid meta event type 0x" . dechex($byte))
        };

        $result->readBytes($stream);

        return $result;
    }
}
