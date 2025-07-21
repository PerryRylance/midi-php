<?php

namespace PerryRylance\Midi\Streams;

use Exception;
use ParseError;
use RangeException;
use PerryRylance\Midi\Exceptions\ParseException;
use UnexpectedValueException;

class ReadStream extends Stream
{
    public function __construct(private string $buffer)
    {

    }

    public function getLength(): int
    {
        return strlen($this->buffer);
    }

    private function assertPositionIsValid(?int $offset = 0): void
    {
        if($this->position < 0)
            throw new RangeException("Position cannot be negative");

        if($this->position >= $this->getLength() - $offset)
            throw new ParseException("Unexpected end of stream");
    }

    private function unpackAndAdvance(string $format, int $size = 1)
    {
        $this->assertPositionIsValid($size - 1);

        $part = substr($this->buffer, $this->position, $size);

        $array = unpack($format, $part);

        if(empty($array))
            throw new ParseException("Failed to unpack $size bytes with format $format");

        $this->position += $size;

        return $array[1];
    }

    public function readByte(): int
    {
        return $this->unpackAndAdvance(Stream::FORMAT_BYTE);
    }

    public function readByteAssertingValue(int $expected): void
    {
        $actual = $this->readByte();

        if($actual !== $expected)
            throw new UnexpectedValueException();
    }

    public function readSignedByte(): int
    {
        return $this->unpackAndAdvance(Stream::FORMAT_SIGNED_BYTE);
    }

    public function readShort(): int
    {
        return $this->unpackAndAdvance(Stream::FORMAT_SHORT, 2);
    }

    public function readUint(): int
    {
        return $this->unpackAndAdvance(Stream::FORMAT_UINT, 4);
    }

    public function readVlv(): int
    {
        if(($value = $this->readByte()) & 0x80)
        {
            $value &= 0x7F;

            do{
                $value = ($value << 7) + (($c = $this->readByte()) & 0x7F);
            }while($c & 0x80);
        }

        return $value;
    }

    public function seekRelative(int $relative): void
    {
        $this->position += $relative;

        $this->assertPositionIsValid();
    }
}
