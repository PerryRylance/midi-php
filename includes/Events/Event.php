<?php

namespace PerryRylance\Midi\Events;

use InvalidArgumentException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;

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

    protected function setDelta(mixed $value): void
    {
        $this->assertIsInt($value, 'Delta must be an integer');
        $this->assertWithinRange($value, 0, 0x0FFFFFFF);

        $this->_delta = $value;
    }
}
