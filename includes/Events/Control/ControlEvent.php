<?php

namespace PerryRylance\Midi\Events\Control;

use InvalidArgumentException;
use PerryRylance\Midi\Events\Event;#
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;

abstract class ControlEvent extends Event
{
    #[Getter]
    #[Setter]
    protected int $_channel;

    public function __construct(int $delta = 0, int $channel = 0)
    {
        parent::__construct($delta);

        $this->setChannel($channel);
    }

    protected abstract function getType(): ControlEventType;

    protected function setChannel($value): void
    {
        $this->assertIsInt($value, 'Channel must be an integer');
        $this->assertWithinRange($value, 0, 0xF);

        $this->_channel = $value;
    }

    protected function writeType(WriteStream $stream, ?StatusBytes $status = null): void
    {
        $hibyte = $this->getType()->value;

        if($status && ($status[0] === $hibyte && $status[1] === $this->_channel))
            return; // NB: Skip, status is the same

        $stream->writeByte($hibyte | $this->_channel);

        if($status)
        {
            $status[0] = $hibyte;
            $status[1] = $this->_channel;
        }
    }
}
