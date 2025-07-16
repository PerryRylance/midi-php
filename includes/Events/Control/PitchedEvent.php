<?php

namespace PerryRylance\Midi\Events\Control;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Traits\AssertsPitch;

abstract class PitchedEvent extends ControlEvent
{
    use AssertsPitch;

    #[Getter]
    #[Setter]
    protected int $_pitch = 60;

    protected function setPitch(mixed $value): void
    {
        $this->assertIsInt($value);
        $this->assertPitch($value);

        $this->_pitch = $value;
    }
}
