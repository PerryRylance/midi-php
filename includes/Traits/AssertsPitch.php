<?php

namespace PerryRylance\Midi\Traits;

trait AssertsPitch
{
	use AssertsIntegerTypes;

	protected function assertPitch($value): void
	{
		$this->assertIsInt($value);
		$this->assertWithinRange($value, 0, 0x7F);
	}
}
