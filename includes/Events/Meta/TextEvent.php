<?php

namespace PerryRylance\Midi\Events\Meta;

use InvalidArgumentException;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use LogicException;
use OverflowException;
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

    protected function setText(string $value)
    {
        $this->assertValidText($value);

        $this->_text = $value;
    }

    protected function assertValidText(string $value): void
    {
        if(strlen($value) > 255)
            throw new OverflowException('Text too long');

        if(preg_match('/[^\x00-\x7F]/', $value))
            throw new InvalidArgumentException('One or more characters are not valid ASCII');
    }

    public function readBytes(ReadStream $stream): void
    {
        $buffer = "";
        $length = $stream->readByte();

        for($i = 0; $i < $length; $i++)
            $buffer .= chr($stream->readByte());

        $this->text = $buffer;
    }
}
