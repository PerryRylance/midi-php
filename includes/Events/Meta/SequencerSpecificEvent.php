<?php

namespace PerryRylance\Midi\Events\Meta;

use LengthException;
use OutOfRangeException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class SequencerSpecificEvent extends MetaEvent
{
	#[Getter]
	#[Setter]
	protected DeviceManufacturer $_manufacturer;

	#[Getter]
	#[Setter]
	protected string $_bytes;

	public function readBytes(ReadStream $stream): void
	{
		$length = $stream->readByte();

		$manufacturer = DeviceManufacturer::tryFrom($stream->readByte());

		if ($manufacturer === null) {
			throw new ParseException('Invalid manufacturer');
		}

		$this->_manufacturer = $manufacturer;

		$this->_bytes = "";

		for ($i = 1; $i < $length; $i++) {
			$this->_bytes .= chr($stream->readByte());
		}
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);

		$length = strlen($this->_bytes);

		$stream->writeByte($length + 1); // NB: Add 1 for manufacturer

		$stream->writeByte($this->_manufacturer->value);

		for ($i = 0; $i < $length; $i++) {
			$stream->writeByte(ord($this->_bytes[$i]));
		}
	}

	protected function setBytes(string $value): void
	{
		if (strlen($value) > 0xFE) {
			throw new LengthException('Maximum length exceeded');
		}
		
		$this->_bytes = $value;
	}

	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::SEQUENCER_SPECIFIC;
	}
}
