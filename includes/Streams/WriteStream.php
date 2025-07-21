<?php

namespace PerryRylance\Midi\Streams;

use PerryRylance\Midi\Traits\AssertsIntegerTypes;

class WriteStream extends Stream
{
	use AssertsIntegerTypes;

	private string $buffer = "";

	private function writeBytes(string $packed): void
	{
		$length = strlen($packed);
		
		for ($i = 0; $i < $length; $i++) {
			$this->buffer[$this->position++] = $packed[$i];
		}
	}

	public function writeByte(int $value): void
	{
		$this->assertByte($value);
		$this->writeBytes(pack(Stream::FORMAT_BYTE, $value));
	}

	public function writeSignedByte(int $value): void
	{
		$this->assertWithinRange($value, -128, 128);
		$this->writeBytes(pack(Stream::FORMAT_SIGNED_BYTE, $value));
	}

	public function writeShort(int $value): void
	{
		$this->assertShort($value);
		$this->writeBytes(pack(Stream::FORMAT_SHORT, $value));
	}

	public function writeUint(int $value): void
	{
		$this->assertUint($value);
		$this->writeBytes(pack(Stream::FORMAT_UINT, $value));
	}

	public function writeVlv(int $value): void
	{
		$buffer = $value & 0x7F;

		while (($value >>= 7) > 0) {
			$buffer <<= 8;
			$buffer |= 0x80;
			$buffer += ($value & 0x7F);
		}

		while (1) {
			$this->writeByte($buffer & 0xFF);

			if ($buffer & 0x80) {
				$buffer >>= 8;
			} else {
				break;
			}
		}
	}

	public function seekTo(mixed $position): void
	{
		$this->assertIsInt($position);
		$this->assertWithinRange($position, 0, strlen($this->buffer));

		$this->position = $position;
	}

	public function toBinary(): string
	{
		return $this->buffer;
	}

	public function toArray(): array
	{
		return array_values(unpack('C*', $this->buffer));
	}

	public function toHexArray(): array
	{
		return array_map(fn (int $value) => sprintf("0x%02X", $value), $this->toArray());
	}
}
