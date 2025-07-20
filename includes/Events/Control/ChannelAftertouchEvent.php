<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Traits\AssertsVelocityLike;

class ChannelAftertouchEvent extends ControlEvent
{
    use AssertsVelocityLike;

    #[Getter]
    #[Setter]
    protected int $_pressure = 127;

    public function readBytes(ReadStream $stream): void
    {
        $this->pressure = $stream->readByte();
    }

    public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
    {
        parent::writeBytes($stream, $status);

        $stream->writeByte($this->pressure);
    }

    protected function setPressure(mixed $value): void
    {
        $this->assertVelocityLike($value);

        $this->_pressure = $value;
    }

    protected function getType(): ControlEventType
    {
        return ControlEventType::CHANNEL_AFTERTOUCH;
    }
}
