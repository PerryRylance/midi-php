<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Streams\StatusBytes;

class EndOfTrackEvent extends MetaEvent
{
	public function readBytes(ReadStream $stream): void
	{
		$stream->readByteAssertingValue(0);
	}

	public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
	{
		parent::writeBytes($stream, $status);

		$stream->writeByte(0);
	}

	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::END_OF_TRACK;
	}
}
