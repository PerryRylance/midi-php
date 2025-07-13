<?php

namespace PerryRylance\Midi\Events\Meta;

use LengthException;
use OutOfRangeException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;

class SequencerSpecificEvent extends MetaEvent
{
    #[Getter]
    #[Setter('byte')]
    protected DeviceManufacturer $_manufacturer;

    #[Getter]
    #[Setter]
    protected string $_bytes;

    public function readBytes(ReadStream $stream): void
    {
        $length = $stream->readByte();

        $manufacturer = DeviceManufacturer::tryFrom($stream->readByte());

        if($manufacturer === null)
            throw new ParseException('Invalid manufacturer');

        $this->_manufacturer = $manufacturer;

        $this->_bytes = "";

        for($i = 1; $i < $length; $i++)
            $this->_bytes .= chr($stream->readByte());
    }

    protected function setBytes(string $value): void
    {
        if(strlen($value) > 0xFE)
            throw new LengthException('Maximum length exceeded');
        
        $this->_bytes = $value;
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::SEQUENCER_SPECIFIC;
    }
}
