<?php

namespace PerryRylance\Midi\Events\Meta;

class TextEvent extends MetaEvent
{
    

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::TEXT;
    }
}
