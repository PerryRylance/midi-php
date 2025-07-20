<?php

namespace PerryRylance\Midi\Events\Meta;

use InvalidArgumentException;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use LogicException;
use OverflowException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;
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

    protected function setText(string $value, bool $strictAscii = true)
    {
        $this->assertValidText($value, $strictAscii);

        $this->_text = $value;
    }

    protected function assertValidText(string $value, bool $strictAscii = true): void
    {
        if(strlen($value) > 255)
            throw new OverflowException('Text too long');

        if($strictAscii && preg_match('/[^\x00-\x7F]/', $value))
            throw new InvalidArgumentException('One or more characters are not valid ASCII');
    }

    public function readBytes(ReadStream $stream): void
    {
        $buffer = "";
        $length = $stream->readByte();

        for($i = 0; $i < $length; $i++)
            $buffer .= chr($stream->readByte());

        // NB: Non-ASCII characters should be supported / ignored during read operations - but we should warn or suppress on write. See https://www.lim.di.unimi.it/IEEE/MIDI/META.HTM?utm_source=chatgpt.com#01-
        $this->setText($buffer, false);
    }
    
    public function writeBytes(WriteStream $stream, ?StatusBytes $status = null): void
    {
        parent::writeBytes($stream);

        $length = strlen($this->_text);

        $stream->writeByte($length);

        for($i = 0; $i < $length; $i++)
            $stream->writeByte(ord($this->_text[$i]));
    }

    // TODO: Strict ASCII mode whne writing
}
