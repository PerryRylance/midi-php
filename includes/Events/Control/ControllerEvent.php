<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

class ControllerEvent extends ControlEvent
{
    public ControllerType $controller;

    #[Getter]
    #[Setter('byte')]
    protected int $_value = 0;

    protected function getType(): ControlEventType
    {
        return ControlEventType::CONTROLLER;
    }

    public function readBytes(ReadStream $stream): void
    {
        $byte = $stream->readByte();
        $controller = ControllerType::tryFrom($byte);

        if($controller === null)
            throw new ParseException('Invalid controller type 0x' . dechex($byte));

        $this->controller = $controller;

        $this->value = $stream->readByte();
    }
}
