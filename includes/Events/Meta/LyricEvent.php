<?php

namespace PerryRylance\Midi\Events\Meta;

class LyricEvent extends TextEvent
{
	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::LYRIC;
	}
}
