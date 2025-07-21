<?php

namespace PerryRylance\Midi\Streams;

use BadMethodCallException;
use PerryRylance\Midi\Traits\AssertsIntegerTypes;
use SplFixedArray;

final class StatusBytes extends SplFixedArray
{
	use AssertsIntegerTypes;

	public function __construct()
	{
		parent::__construct(2);

		$this[0] = $this[1] = 0;
	}

	public function offsetSet($index, mixed $value): void
	{
		$this->assertByte($value);

		parent::offsetSet($index, $value);
	}

	public function setSize(int $size): true
	{
		throw new BadMethodCallException('StatusBytes cannot be resized');
	}
}
