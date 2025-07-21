<?php

namespace PerryRylance\Midi\Collections;

use Aeviiq\Collection\ObjectCollection;
use PerryRylance\Midi\Track;

class TrackCollection extends ObjectCollection
{
    const MAX_COUNT = 0xFFFF;

    protected function allowedInstance(): string
    {
        return Track::class;
    }
}
