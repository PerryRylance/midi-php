<?php

namespace PerryRylance\Midi\Validators;

use PerryRylance\Midi\Collections\TrackCollection;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\File;
use PerryRylance\Midi\FileType;
use PerryRylance\Midi\Track;

class FileValidator
{
	public function __construct(private File $file)
	{
		
	}

	public function validateTrackCount(): void
	{
		if ($this->file->type === FileType::TYPE_0 && $this->file->tracks->count() > 1) {
			throw new ValidationException("MIDI type 0 must have exactly one track");
		}

		if ($this->file->tracks->count() > TrackCollection::MAX_COUNT) {
			throw new ValidationException("Maximum number of tracks exceeded");
		}
	}

	// TODO: Bit misleading as we have a track validator, should this validateCopyrightEvent instead?
	public function validateTrack(Track $track, int $index): void
	{
		if ($index === 0) {
			return;
		}

		if ($track->events->filter(fn (Event $event) => $event instanceof CopyrightEvent)->count()) {
			throw new ValidationException('Copyright event must be on first track');
		}
	}
}
