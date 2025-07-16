<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Attributes\Type;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;

/**
 * @property int $channel
 */
class ChannelPrefixEvent extends MetaEvent
{
    #[Getter]
    #[Setter]
    protected int $_channel = 0;

    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(1);

        $this->channel = $stream->readByte();
    }

    protected function setChannel(int $value): void
    {
        $this->assertWithinRange($value, 0, 0xF);

        $this->_channel = $value;
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::CHANNEL_PREFIX;
    }
}
