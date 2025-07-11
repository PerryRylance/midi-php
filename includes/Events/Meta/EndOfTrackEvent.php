<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Streams\ReadStream;

class EndOfTrackEvent extends MetaEvent
{
    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(0);
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::END_OF_TRACK;
    }
}
