<?php

namespace PerryRylance\Midi\Traits;

trait AssertsVelocityLike
{
    use AssertsIntegerTypes;

    protected function assertVelocityLike(int $value): void
    {
        $this->assertWithinRange($value, 0, 0x7F);
    }
}
