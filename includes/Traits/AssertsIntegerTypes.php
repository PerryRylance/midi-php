<?php

namespace PerryRylance\Midi\Traits;

use OutOfRangeException;

trait AssertsIntegerTypes
{
    private function assertWithinRange(int $value, int $min, int $max): void
    {
        if($value < $min || $value > $max)
            throw new OutOfRangeException("$value is not within the range $min - $max");
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
