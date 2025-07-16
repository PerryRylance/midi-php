<?php

namespace PerryRylance\Midi\Events\Control;

class NoteOffEvent extends NoteEvent
{
    protected function getType(): ControlEventType
    {
        return ControlEventType::NOTE_OFF;
    }
}
