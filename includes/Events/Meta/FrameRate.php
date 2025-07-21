<?php

namespace PerryRylance\Midi\Events\Meta;

enum FrameRate : int
{
	case FPS_24         = 0x0;
	case FPS_25         = 0x1;
	case FPS_DROP_30    = 0x2;
	case FPS_30         = 0x3;
}
