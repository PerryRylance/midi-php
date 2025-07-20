<?php

namespace PerryRylance\Midi\Collections;

use Aeviiq\Collection\ObjectCollection;
use PerryRylance\Midi\Track;

class TrackCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return Track::class;
    }
}
