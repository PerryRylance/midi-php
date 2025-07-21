<?php

namespace PerryRylance\Midi\Events\SysEx;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Meta\DeviceManufacturer;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Events\EventType;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class SysExEvent extends Event
{
	#[Getter]
	#[Setter]
	protected DeviceManufacturer | UniversalDevices $_manufacturer;

	#[Getter]
	#[Setter]
	protected string $_bytes = ""; // NB: Payload not including 0xF7 terminator

	public function readBytes(ReadStream $stream): void
	{
		$byte = $stream->readByte();

		if (($manufacturer = DeviceManufacturer::tryFrom($byte)) || ($manufacturer = UniversalDevices::tryFrom($byte))) {
			$this->_manufacturer = $manufacturer;
		} else {
			throw new ParseException("Invalid manufacturer");
		}

		$this->_bytes = "";

		while (($byte = $stream->readByte()) !== 0xF7) {
			$this->_bytes .= chr($byte);
		}
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);
		
		$stream->writeByte($this->_manufacturer->value);

		$length = strlen($this->_bytes);

		for ($i = 0; $i < $length; $i++) {
			$stream->writeByte(ord($this->_bytes[$i]));
		}

		$stream->writeByte(0xF7);
	}

	protected function writeType(WriteStream $stream, ?StatusBytes $status = null): void
	{
		$stream->writeByte(EventType::SYSEX->value);
	}
}
