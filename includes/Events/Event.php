<?php

namespace PerryRylance\Midi\Events;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;

abstract class Event
{
    use PropertyAccessors;

    #[Getter]
    #[Setter]
    private int $_delta = 0;

    public function __construct(int $delta = 0)
    {
        $this->setDelta($delta);
    }

    abstract public function readBytes(ReadStream $stream): void;

    protected function setDelta(int $value): void
    {
        $this->assertWithinRange($value, 0, 0x0FFFFFFF);

        $this->_delta = $value;
    }
}
