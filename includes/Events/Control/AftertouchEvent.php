<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\AssertsVelocityLike;

class AftertouchEvent extends PitchedEvent
{
    use AssertsVelocityLike;

    #[Getter]
    #[Setter]
    protected int $_pressure = 127;

    protected function getType(): ControlEventType
    {
        return ControlEventType::AFTERTOUCH;
    }

    protected function setPressure(int $value): void
    {
        $this->assertVelocityLike($value);

        $this->_pressure = $value;
    }

    public function readBytes(ReadStream $stream): void
    {
        $this->pitch = $stream->readByte();
        $this->pressure = $stream->readByte();
    }
}
