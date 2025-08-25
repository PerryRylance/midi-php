<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

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

		if ($this->_quality === null) {
			throw new ParseException($stream, "Invalid quality in key signature event");
		}
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);
		
		$stream->writeByte(2);

		$stream->writeSignedByte($this->accidentals);
		$stream->writeByte($this->quality->value);
	}

	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::KEY_SIGNATURE;
	}

	protected function setAccidentals(int $value)
	{
		$this->assertWithinRange($value, -7, 7);

		$this->_accidentals = $value;
	}
}
