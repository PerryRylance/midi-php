<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Traits\AssertsIntegerTypes;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Attributes\Type;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Traits\PropertyAccessors;

/**
 * @property int $number
 */
class SequenceNumberEvent extends MetaEvent
{
	#[Getter]
	#[Setter(Type::SHORT)]
	protected $_number = 0;

	public function readBytes(ReadStream $stream): void
	{
		$stream->readByteAssertingValue(2);

		$this->_number = $stream->readShort();
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);

		$stream->writeByte(2);

		$stream->writeShort($this->number);
	}

	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::SEQUENCE_NUMBER;
	}
}
