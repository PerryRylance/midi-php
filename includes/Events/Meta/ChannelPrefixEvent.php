<?php

namespace PerryRylance\Midi\Events\Meta;

class ChannelPrefixEvent extends MetaEvent
{
    protected $_channel = 0;

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::CHANNEL_PREFIX;
    }
}
