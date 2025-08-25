<?php

namespace PerryRylance\Midi\Collections;

use PerryRylance\Midi\Events\Event;

final class EventCollection extends Collection
{
	protected function allowedInstance(): string
	{
		return Event::class;
	}
}
