<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Events\Event;

abstract class MetaEvent extends Event
{
    protected abstract function getMetaType(): MetaEventType;
}
