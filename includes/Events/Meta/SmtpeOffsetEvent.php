<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

class SmtpeOffsetEvent extends MetaEvent
{
    public FrameRate $rate = FrameRate::FPS_24;
    public int $hours = 1;
    public int $minutes = 0;
    public int $seconds = 0;
    public int $frames = 0;
    public int $subframes = 0;

    // TODO: Private and test parameters please
	// TODO: See spec http://www.somascape.org/midi/tech/mfile.html#:~:text=SMPTE%20Offset,-FF%2054%2005&text=ff%20is%20a%20byte%20specifying,prior%20to%20any%20MIDI%20events.

    public function readBytes(ReadStream $stream): void
    {
        $length = $stream->readByte();

        if($length !== 5)
            throw new ParseException('Expected length to be 5');

        // NB: The fourth byte specifies the hours of the SMPTE time and the frame rate
		// NB: This byte has the binary format "0sshhhhh". The top bit is zero as it is reserved according to the MIDI time code specifications.
        $byte = $stream->readByte();

        // NB: The two bits ss define the frame rate in frames per second.
        $this->rate = FrameRate::tryFrom( ($byte & 0x60) >> 5 );

        // NB: The five hhhhh bits define the hours of the SMPTE time.
        $this->hours = $byte & 0x1F;

        $this->minutes = $stream->readByte();
        $this->seconds = $stream->readByte();
        $this->frames = $stream->readByte();
        $this->subframes = $stream->readByte();
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::SMPTE_OFFSET;
    }
}
