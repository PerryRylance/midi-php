<?php

namespace PerryRylance\Midi\Traits;

use RangeException;

trait AssertsIntegerTypes
{
    private function assertWithinRange(int $value, int $min, int $max): void
    {
        if($value < $min || $value > $max)
            throw new RangeException();
    }

    protected function assertByte(int $value): void
    {
        $this->assertWithinRange($value, 0, 0xFF);
    }

    protected function assertShort(int $value): void
    {
        $this->assertWithinRange($value, 0, 0xFFFF);
    }

    protected function assertUint(int $value): void
    {
        $this->assertWithinRange($value, 0, 0xFFFFFFFF);
    }
}
