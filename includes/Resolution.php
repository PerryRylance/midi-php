<?php

namespace PerryRylance\Midi;

use LogicException;
use OutOfRangeException;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Events\Meta\FrameRate;
use PerryRylance\Midi\Exceptions\ResolutionException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use UnexpectedValueException;

/**
 * @property ResolutionUnits $units
 * @property int $ticksPerQuarterNote
 * @property FrameRate $framesPerSecond
 * @property int $ticksPerFrame
 */
class Resolution
{
    use PropertyAccessors;

    private int $_value = 480;

    public function readBytes(ReadStream $stream): void
    {
        $value = $stream->readShort();

        if((0x8000 & $value) === 0x8000)
            $this->assertValidFrameRateFromShort($value);

        // TODO: Should there not be more checks here?

        $this->_value = $value;
    }

    public function writeBytes(WriteStream $stream): void
    {
        $stream->writeShort($this->_value);
    }

    #[Property('units')]
    protected function getUnits(): ResolutionUnits
    {
        return (0x8000 & $this->_value) === 0x8000 ? ResolutionUnits::FPS : ResolutionUnits::PPQ;
    }

    #[Property('ticksPerQuarterNote')]
    protected function getTicksPerQuarterNote(): int
    {
        if($this->units !== ResolutionUnits::PPQ)
            throw new ResolutionException('Cannot get PPQ from FPS resolution');

        return $this->_value;
    }

    #[Property('ticksPerQuarterNote')]
    protected function setTicksPerQuarterNote(mixed $value): void
    {
        $this->assertIsInt($value);
        
        if($value < 1)
            throw new OutOfRangeException('PPQ must be non-zero');

        // TODO: Not sure about the bitwise here, why?
        if(($value & 0x8000) === 0x8000)
            throw new OutOfRangeException('PPQ cannot exceed 0x8000 (32,768)');

        $this->_value = $value;
    }

    #[Property('ticksPerFrame')]
    protected function getTicksPerFrame(): int
    {
        if($this->units !== ResolutionUnits::FPS)
            throw new ResolutionException('Cannot get ticks-per-frame from PPQ resolution');

        return $this->_value & 0xFF;
    }

    #[Property('ticksPerFrame')]
    protected function setTicksPerFrame(mixed $value): void
    {
        if($this->units !== ResolutionUnits::FPS)
            throw new ResolutionException('Cannot set ticks-per-frame on PPQ resolution, did you mean to use the setFps method?');

        $this->setFps($this->framesPerSecond, $value);
    }

    #[Property('framesPerSecond')]
    protected function getFramesPerSecond(): FrameRate
    {
        if($this->units !== ResolutionUnits::FPS)
            throw new ResolutionException();

        return $this->getFrameRateFromShort($this->_value);
    }

    #[Property('framesPerSecond')]
    protected function setFramesPerSecond(FrameRate $value): void
    {
        if($this->units !== ResolutionUnits::FPS)
            throw new ResolutionException('Cannot set FPS on PPQ resolution, did you mean to use the setFps method?');

        $this->setFps($value, $this->ticksPerFrame);
    }

    public function setFps(FrameRate $frameRate, mixed $ticksPerFrame): void
    {
        $this->assertIsInt($ticksPerFrame);
        $this->assertWithinRange($ticksPerFrame, 1, 0xFF);

        $this->_value = 0x8000 | (0x7F00 & ($frameRate->value << 8)) | $ticksPerFrame;
    }

    private function assertValidFrameRateFromShort(int $value): void
    {
        if(FrameRate::tryFrom(($value & 0x7FFF) >> 8) !== null)
            return;

        throw new UnexpectedValueException();
    }

    private function getFrameRateFromShort(int $value): FrameRate
    {
        $this->assertValidFrameRateFromShort($value);

        return FrameRate::tryFrom(($value & 0x7FFF) >> 8);
    }
}
