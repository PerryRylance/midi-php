<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Attributes\Type;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class PortPrefixEvent extends MetaEvent
{
	#[Getter]
	#[Setter(Type::BYTE)]
	protected int $_port = 0;

	public function readBytes(ReadStream $stream): void
	{
		$stream->readByteAssertingValue(1);

		$this->_port = $stream->readByte();
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);
		
		$stream->writeByte(1);

		$stream->writeByte($this->port);
	}

	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::PORT_PREFIX;
	}
}
