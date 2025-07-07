<?php

namespace PerryRylance\Midi\Events\Meta;

class MarkerEvent extends TextEvent
{
    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::MARKER;
    }
}
