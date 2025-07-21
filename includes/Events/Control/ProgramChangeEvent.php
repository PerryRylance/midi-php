<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class ProgramChangeEvent extends ControlEvent
{
	public ProgramType $program = ProgramType::ACOUSTIC_GRAND_PIANO;

	public function readBytes(ReadStream $stream): void
	{
		$byte = $stream->readByte();
		$program = ProgramType::tryFrom($byte);

		if ($program === null) {
			throw new ParseException('Invalid program type 0x' . dechex($byte));
		}

		$this->program = $program;
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);

		$stream->writeByte($this->program->value);
	}

	protected function getType(): ControlEventType
	{
		return ControlEventType::PROGRAM_CHANGE;
	}
}
