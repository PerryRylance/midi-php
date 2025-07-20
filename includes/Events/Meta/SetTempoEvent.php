<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;
use RangeException;

/**
 * @property int|float $bpm
 */
class SetTempoEvent extends MetaEvent
{
    const MICROSECOND_PER_MINUTE = 60000000;

    protected int $mspqn;

    public function __construct()
    {
        $this->mspqn = 120 * self::MICROSECOND_PER_MINUTE;
    }

    #[Property("bpm")]
    protected function getBpm()
    {
        return self::MICROSECOND_PER_MINUTE / $this->mspqn;
    }

    #[Property("bpm")]
    protected function setBpm(int | float $value)
    {
        $mspqn = round(self::MICROSECOND_PER_MINUTE / $value);

        if($mspqn <= 0 || $mspqn > 0xFFFFFF)
            throw new RangeException("Calculated MSPQN out of range");

        $this->mspqn = $mspqn;
    }

    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(3);

        $a = $stream->readByte();
        $b = $stream->readByte();
        $c = $stream->readByte();

        $this->mspqn = ($a << 16) | ($b << 8) | $c;
    }

    public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
    {
        parent::writeBytes($stream, $status);

        $stream->writeByte(3);

        $stream->writeByte(($this->mspqn >> 16) & 0xFF);
        $stream->writeByte(($this->mspqn >> 8) & 0xFF);
        $stream->writeByte($this->mspqn & 0xFF);
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::SET_TEMPO;
    }
}
