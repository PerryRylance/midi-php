<?php

namespace PerryRylance\Midi\Streams;

abstract class Stream
{
    protected int $position = 0;

    public function getPosition(): int
    {
        return $this->position;
    }
}
