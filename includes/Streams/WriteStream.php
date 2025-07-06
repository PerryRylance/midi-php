<?php

namespace PerryRylance\Midi\Streams;

use PerryRylance\Midi\Traits\AssertsIntegerTypes;

class WriteStream extends Stream
{
    use AssertsIntegerTypes;

    private string $buffer = "";

    public function writeByte(int $value): void
    {
        $this->assertByte($value);

        $this->buffer .= pack(Stream::FORMAT_BYTE, $value);
        $this->position++; // TODO: Is this even necessary?
    }

    public function writeShort(int $value): void
    {
        $this->assertShort($value);

        $this->buffer .= pack(Stream::FORMAT_SHORT, $value);
        $this->position += 2;
    }

    public function writeUint(int $value): void
    {
        $this->assertUint($value);

        $this->buffer .= pack(Stream::FORMAT_UINT, $value);
        $this->position += 4;
    }

    public function writeVlv(int $value): void
    {
        $buffer = $value & 0x7F;

        while(($value >>= 7) > 0)
        {
            $buffer <<= 8;
            $buffer |= 0x80;
            $buffer += ($value & 0x7F);
        }

        while(1)
        {
            $this->writeByte($buffer & 0xFF);

            if($buffer & 0x80)
                $buffer >>= 8;
            else
                break;
        }
    }

    public function toBinary(): string
    {
        return $this->buffer;
    }
}
