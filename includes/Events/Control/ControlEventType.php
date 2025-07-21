<?php

namespace PerryRylance\Midi\Events\Control;

enum ControlEventType : int
{
	case NOTE_OFF = 0x80;
	case NOTE_ON = 0x90;
	case AFTERTOUCH = 0xA0;
	case CONTROLLER = 0xB0;
	case PROGRAM_CHANGE = 0xC0;
	case CHANNEL_AFTERTOUCH = 0xD0;
	case PITCH_WHEEL = 0xE0;
}
