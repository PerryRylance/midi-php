<?php

namespace PerryRylance\Midi\Events\Meta;

class InstrumentNameEvent extends TextEvent
{
	protected function getMetaType(): MetaEventType
	{
		return MetaEventType::INSTRUMENT_NAME;
	}
}
