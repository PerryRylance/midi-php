<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\EventType;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

abstract class MetaEvent extends Event
{
	abstract protected function getMetaType(): MetaEventType;

	protected function writeType(WriteStream $stream, ?StatusBytes $status = null): void
	{
		$stream->writeByte(EventType::META->value);
		$stream->writeByte($this->getMetaType()->value);
	}
}
