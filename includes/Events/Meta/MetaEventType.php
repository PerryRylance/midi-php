<?php

namespace PerryRylance\Midi\Events\Meta;

enum MetaEventType : int
{
	case SEQUENCE_NUMBER	= 0x00;
	case TEXT				= 0x01;
	case COPYRIGHT			= 0x02;
	case TRACK_NAME			= 0x03;
	case INSTRUMENT_NAME	= 0x04;
	case LYRIC				= 0x05;
	case MARKER				= 0x06;
	case CUE_POINT			= 0x07;
	case CHANNEL_PREFIX		= 0x20;
	case PORT_PREFIX		= 0x21;
	case END_OF_TRACK		= 0x2F;
	case SET_TEMPO			= 0x51;
	case SMPTE_OFFSET		= 0x54;
	case TIME_SIGNATURE		= 0x58;
	case KEY_SIGNATURE		= 0x59;
	case SEQUENCER_SPECIFIC	= 0x7F;
}
