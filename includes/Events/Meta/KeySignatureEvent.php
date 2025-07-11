<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;

class KeySignatureEvent extends MetaEvent
{
    #[Getter]
    #[Setter]
    protected int $_accidentals;

    #[Getter]
    #[Setter]
    protected Quality $_quality;

    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(2);

        $this->_accidentals = $stream->readSignedByte();
        $this->_quality = Quality::tryFrom($stream->readByte());

        if($this->_quality === null)
            throw new ParseException("Invalid quality in key signature event");
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::KEY_SIGNATURE;
    }
}
