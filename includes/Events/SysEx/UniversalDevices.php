<?php

namespace PerryRylance\Midi\Events\SysEx;

enum UniversalDevices : int
{
    case NON_REAL_TIME = 0x7E;
    case REAL_TIME = 0x7F;
}
