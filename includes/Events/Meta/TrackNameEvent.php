<?php

namespace PerryRylance\Midi\Events\Meta;

class TrackNameEvent extends TextEvent
{
    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::TRACK_NAME;
    }
}
