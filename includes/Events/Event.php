<?php

namespace PerryRylance\Midi\Events;

use InvalidArgumentException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;

abstract class Event
{
    use PropertyAccessors;

    #[Getter]
    #[Setter]
    protected int $_delta = 0;

    public function __construct($delta = 0)
    {
        $this->setDelta($delta);
    }

    abstract public function readBytes(ReadStream $stream): void;

    protected abstract function writeType(WriteStream $stream, ?StatusBytes $status = null): void;

    public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
    {
        $this->writeType($stream, $status);
    }

    protected function setDelta(mixed $value): void
    {
        $this->assertIsInt($value, 'Delta must be an integer');
        $this->assertWithinRange($value, 0, 0x0FFFFFFF);

        $this->_delta = $value;
    }
}
