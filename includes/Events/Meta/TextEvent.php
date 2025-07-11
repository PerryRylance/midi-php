<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use LogicException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;
use RangeException;

/**
 * @property string $text
 */
class TextEvent extends MetaEvent
{
    #[Getter]
    #[Setter]
    protected string $_text = "";

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::TEXT;
    }

    protected function setText($value)
    {
        $this->assertValidText($value);

        $this->_text = $value;
    }

    protected function assertValidText(string $value): void
    {
        if(strlen($value) > 255)
            throw new RangeException('Text too long');

        if(!preg_match('/^[\x00-\xFF]*$/', $value))
            throw new LogicException('One or more characters are not valid ASCII');
    }

    public function readBytes(ReadStream $stream): void
    {
        $buffer = "";
        $length = $stream->readByte();

        for($i = 0; $i < $length; $i++)
            $buffer .= chr($stream->readByte());

        $this->_text = $buffer;
    }
}
