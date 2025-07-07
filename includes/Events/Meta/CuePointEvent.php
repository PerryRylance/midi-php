<?php

namespace PerryRylance\Midi\Events\Meta;

class CuePointEvent extends TextEvent
{
    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::CUE_POINT;
    }
}
