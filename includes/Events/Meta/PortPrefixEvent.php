<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Attributes\Type;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\ReadStream;

class PortPrefixEvent extends MetaEvent
{
    #[Getter]
    #[Setter(Type::BYTE)]
    protected int $_port = 0;

    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(1);

        $this->_port = $stream->readByte();
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::PORT_PREFIX;
    }
}
