<?php

namespace PerryRylance\Midi\Events;

enum EventType : int
{
	case CONTROL = 0;
	case SYSEX = 0xF0;
	case META = 0xFF;
}
