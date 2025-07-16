<?php

namespace PerryRylance\Midi\Traits;

trait AssertsVelocityLike
{
    use AssertsIntegerTypes;

    protected function assertVelocityLike($value): void
    {
        $this->assertIsInt($value);
        $this->assertWithinRange($value, 0, 0x7F);
    }
}
