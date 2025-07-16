<?php

namespace PerryRylance\Midi\Traits;

trait AssertsPitch
{
    use AssertsIntegerTypes;

    protected function assertPitch(int $value): void
    {
        $this->assertWithinRange($value, 0, 0x7F);
    }
}
