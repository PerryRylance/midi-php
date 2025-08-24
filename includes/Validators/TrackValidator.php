<?php

namespace PerryRylance\Midi\Validators;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Events\Meta\SequenceNumberEvent;
use PerryRylance\Midi\Events\Meta\TrackNameEvent;
use PerryRylance\Midi\Exceptions\InvalidTrackSizeException;
use PerryRylance\Midi\Exceptions\MissingEndOfTrackException;
use PerryRylance\Midi\Exceptions\NonZeroAbsoluteTimeException;
use PerryRylance\Midi\Exceptions\PrematureEndOfTrackException;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\Track;

class TrackValidator
{
	public function __construct(private Track $track)
	{
		
	}

	private function assertZeroAbsoluteTime(int $index): void
	{
		for ($i = $index; $i >= 0; $i--) {
			if ($this->track->events[$i]->delta > 0) {
				throw new NonZeroAbsoluteTimeException('Event must have zero delta time and cannot occur after non-zero delta time events');
			}
		}
	}

	public function assertValidSize(int $size): void
	{
		if ($size > 0xFFFFFFFF) {
			throw new InvalidTrackSizeException();
		}
	}

	public function validateEvent(Event $event, int $index): void
	{
		if ($event instanceof EndOfTrackEvent && $index !== $this->track->events->count() - 1)
			throw new PrematureEndOfTrackException();

		if ($index === $this->track->events->count() - 1 && !($event instanceof EndOfTrackEvent))
			throw new MissingEndOfTrackException();

		if (
			$event instanceof CopyrightEvent ||
			$event instanceof SequenceNumberEvent ||
			$event instanceof TrackNameEvent
		) {
			$this->assertZeroAbsoluteTime($index);
		}
	}
}
