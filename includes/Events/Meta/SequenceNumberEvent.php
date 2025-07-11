<?php

namespace PerryRylance\Midi\Events\Meta;

use PerryRylance\Midi\Traits\AssertsIntegerTypes;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

/**
 * @property int $number
 */
class SequenceNumberEvent extends MetaEvent
{
    use AssertsIntegerTypes;

    #[Getter]
    #[Setter]
    protected $_number = 0;

    protected function setNumber(int $value)
    {
        $this->assertShort($value);

        $this->_number = $value;
    }

    public function readBytes(ReadStream $stream): void
    {
        $stream->readByteAssertingValue(2);

        $this->_number = $stream->readShort();
    }

    protected function getMetaType(): MetaEventType
    {
        return MetaEventType::SEQUENCE_NUMBER;
    }
}
