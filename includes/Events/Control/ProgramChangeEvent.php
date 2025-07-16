<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

class ProgramChangeEvent extends ControlEvent
{
    public ProgramType $program = ProgramType::ACOUSTIC_GRAND_PIANO;

    public function readBytes(ReadStream $stream): void
    {
        $byte = $stream->readByte();
        $program = ProgramType::tryFrom($byte);

        if($program === null)
            throw new ParseException('Invalid program type 0x' . dechex($byte));

        $this->program = $program;
    }

    protected function getType(): ControlEventType
    {
        return ControlEventType::PROGRAM_CHANGE;
    }
}
