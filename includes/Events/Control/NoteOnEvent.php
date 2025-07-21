<?php

namespace PerryRylance\Midi\Events\Control;

class NoteOnEvent extends NoteEvent
{
	protected function getType(): ControlEventType
	{
		return ControlEventType::NOTE_ON;
	}
}
