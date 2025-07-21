<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Traits\AssertsVelocityLike;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

abstract class NoteEvent extends PitchedEvent
{
	use AssertsVelocityLike;

	#[Getter]
	#[Setter]
	protected int $_velocity = 127;

	public function readBytes(ReadStream $stream): void
	{
		$this->pitch = $stream->readByte();
		$this->velocity = $stream->readByte();
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);

		$stream->writeByte($this->pitch);
		$stream->writeByte($this->velocity);
	}

	protected function setVelocity(mixed $value): void
	{
		$this->assertVelocityLike($value);

		$this->_velocity = $value;
	}
}
