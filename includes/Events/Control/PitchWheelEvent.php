<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;

class PitchWheelEvent extends ControlEvent
{
    #[Getter]
    protected int $_value = 0x2000; // NB: Value as a 14-bit number. Signed, but without any sign bit. This value is zero.

    public function readBytes(ReadStream $stream): void
    {
        $first = $stream->readByte();
        $second = $stream->readByte();

        if($first & 0x80)
            throw new ParseException("Expected first bit of first byte to be zero");

        $this->_value = (($second & 0x7F) << 7) | ($first & 0x7F);
    }

    protected function getType(): ControlEventType
    {
        return ControlEventType::PITCH_WHEEL;
    }
}
