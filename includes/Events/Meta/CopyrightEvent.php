<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Traits\PropertyAccessors;

class CopyrightEvent extends TextEvent
{
    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::COPYRIGHT;
    }
}
