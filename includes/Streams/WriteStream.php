<?php

namespace PerryRylance\Midi\Streams;

use RangeException;

class WriteStream extends Stream
{
    private string $buffer = "";

    private function assertWithinRange(int $value, int $min, int $max): void
    {
        if($value < $min || $value > $max)
            throw new RangeException();
    }

    private function assertByte(int $value): void
    {
        $this->assertWithinRange($value, 0, 0xFF);
    }

    public function writeByte(int $value): void
    {
        $this->assertByte($value);

        $this->buffer .= pack(Stream::FORMAT_BYTE, $value);
        $this->position++; // TODO: Is this even necessary?
    }

    public function toBinary(): string
    {
        return $this->buffer;
    }
}
