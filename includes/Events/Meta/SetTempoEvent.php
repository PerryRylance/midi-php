<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;

class SetTempoEvent extends MetaEvent
{
    use PropertyAccessors;

    const MICROSECOND_PER_MINUTE = 60000000;

    private int $mspqn;

    public function __construct()
    {
        parent::__construct();

        $this->mspqn = 120 * self::MICROSECOND_PER_MINUTE;
    }

    public function readBytes(ReadStream $stream): void
    {
        $length = $stream->readByte();

        if($length !== 3)
            throw new ParseException("Expected length to be 3");

        $a = $stream->readByte();
        $b = $stream->readByte();
        $c = $stream->readByte();

        $this->mspqn = ($a << 16) | ($b << 8) | $c;
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::SET_TEMPO;
    }
}
