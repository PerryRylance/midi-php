<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Traits\AssertsVelocityLike;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\ReadStream;

abstract class NoteEvent extends PitchedEvent
{
    use AssertsVelocityLike;

    #[Getter]
    #[Setter]
    protected int $_velocity = 127;

    protected function setVelocity(int $value): void
    {
        $this->assertVelocityLike($value);

        $this->_velocity = $value;
    }

    public function readBytes(ReadStream $stream): void
    {
        $this->pitch = $stream->readByte();
        $this->velocity = $stream->readByte();
    }
}
