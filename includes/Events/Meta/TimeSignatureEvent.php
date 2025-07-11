<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Attributes\Type;
use UnexpectedValueException;

class TimeSignatureEvent extends MetaEvent
{
    #[Getter]
    #[Setter(Type::BYTE)]
    protected int $_numerator = 4;

    #[Getter]
    #[Setter(Type::BYTE)]
    protected int $_denominator = 4;

    #[Getter]
    #[Setter(Type::BYTE)]
    protected int $_ticksPerMetronomeClick = 24;

    #[Getter]
    #[Setter(Type::BYTE)]
    protected int $_num32ndNotesPerBeat = 8;

    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(4);

        $this->setNumerator($stream->readByte());
        $this->setDenominator(pow($stream->readByte(), 2));
        $this->setTicksPerMetronomeClick($stream->readByte());
        $this->setNum32ndNotesPerBeat($stream->readByte());
    }

    protected function setNumerator(int $value): void
    {
        $this->assertNonZero($value);
        
        $this->_numerator = $value;
    }

    protected function setDenominator(int $value): void
    {
        if(log($value, 2) % 1 !== 0)
            throw new UnexpectedValueException("Denominator must be a power of two");

        $this->_denominator = $value;
    }

    protected function setTicksPerMetronomeClick(int $value): void
    {
        $this->assertNonZero($value);

        $this->_ticksPerMetronomeClick = $value;
    }

    protected function setNum32ndNotesPerBeat(int $value): void
    {
        $this->assertNonZero($value);

        $this->_num32ndNotesPerBeat = $value;
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::TIME_SIGNATURE;
    }
}
