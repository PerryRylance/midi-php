<?php

namespace PerryRylance\Midi\Streams;

abstract class Stream
{
	const FORMAT_BYTE = 'C';
	const FORMAT_SIGNED_BYTE = 'c';
	const FORMAT_SHORT = 'n';
	const FORMAT_UINT = 'N';

	protected int $position = 0;

	public function getPosition(): int
	{
		return $this->position;
	}
}
