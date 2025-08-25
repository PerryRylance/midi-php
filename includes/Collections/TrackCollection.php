<?php

namespace PerryRylance\Midi\Collections;

use PerryRylance\Midi\Track;

class TrackCollection extends Collection
{
	const MAX_COUNT = 0xFFFF;

	protected function allowedInstance(): string
	{
		return Track::class;
	}
}
