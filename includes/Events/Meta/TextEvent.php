<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use LogicException;
use RangeException;

/**
 * @property string $text
 */
class TextEvent extends MetaEvent
{
    #[Getter]
    #[Setter]
    private string $_text = "";

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::TEXT;
    }

    private function setText($value)
    {
        $this->assertValidText($value);

        $this->_text = $value;
    }

    private function assertValidText(string $value): void
    {
        if(strlen($value) > 255)
            throw new RangeException('Text too long');

        if(!preg_match('/^[\x00-\xFF]*$/', $value))
            throw new LogicException('One or more characters are not valid ASCII');
    }
}
