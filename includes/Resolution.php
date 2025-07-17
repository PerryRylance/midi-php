<?php

namespace PerryRylance\Midi;

use LogicException;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Traits\PropertyAccessors;

/**
 * @property ResolutionUnits $units
 * @property int $ticksPerQuarterNote
 */
class Resolution
{
    use PropertyAccessors;

    private int $_value = 480;

    #[Property('units')]
    protected function getUnits(): ResolutionUnits
    {
        return (0x8000 & $this->_value) === 0x8000 ? ResolutionUnits::FPS : ResolutionUnits::PPQ;
    }

    #[Property('ticksPerQuarterNote')]
    protected function getTicksPerQuarterNote(): int
    {
        if($this->units !== ResolutionUnits::PPQ)
            throw new LogicException('Cannot get PPQ from FPS resolution');

        return $this->_value;
    }
}
