<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class ControllerEvent extends ControlEvent
{
    public ControllerType $controller;

    #[Getter]
    #[Setter('byte')]
    protected int $_value = 0;

    public function readBytes(ReadStream $stream): void
    {
        $byte = $stream->readByte();
        $controller = ControllerType::tryFrom($byte);

        if($controller === null)
            throw new ParseException('Invalid controller type 0x' . dechex($byte));

        $this->controller = $controller;

        $this->value = $stream->readByte();
    }

    public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
    {
        parent::writeBytes($stream, $status);

        $stream->writeByte($this->controller->value);
        $stream->writeByte($this->value);
    }

    protected function getType(): ControlEventType
    {
        return ControlEventType::CONTROLLER;
    }
}
