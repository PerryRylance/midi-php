<?php

namespace PerryRylance\Midi\Events\Control;

use OutOfRangeException;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class PitchWheelEvent extends ControlEvent
{
	#[Getter]
	protected int $_value = 0x2000; // NB: Value as a 14-bit number. Signed, but without any sign bit. This value is zero.

	public function readBytes(ReadStream $stream): void
	{
		$first = $stream->readByte();
		$second = $stream->readByte();

		if ($first & 0x80) {
			throw new ParseException($stream, "Expected first bit of first byte to be zero");
		}

		$this->_value = (($second & 0x7F) << 7) | ($first & 0x7F);
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);

		// NB: Internal		..012345 6789ABCD
		// NB: Serialized	.789ABCD .0123456

		$left = $this->_value & 0x7F;
		$right = ($this->_value & 0x3F80) >> 7;

		$stream->writeByte($left);
		$stream->writeByte($right);
	}

	protected function getType(): ControlEventType
	{
		return ControlEventType::PITCH_WHEEL;
	}

	// TODO: Test this out please, do we need remapping eg for exponent
	#[Property('amount')]
	protected function getAmount(): float
	{
		if ($this->_value <= 0x2000) {
			$i = 1 + $this->_value;
		} else {
			$i = $this->_value;
		}

		return -1 + 2 * $i / 0x3FFF;
	}

	#[Property('amount')]
	protected function setAmount(float $floating): void
	{
		if ($floating < -1.0 || $floating > 1.0) {
			throw new OutOfRangeException("Expected value within -1 to +1");
		}

		if ($floating > 0) {
			$this->_value = 0x2000 + (round($floating * 0x2000) - 1);
		} else {
			$this->_value = round(($floating + 1) * 0x2000);
		}
	}
}
