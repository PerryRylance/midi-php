<?php

namespace PerryRylance\Midi\Exceptions;

use Exception;
use PerryRylance\Midi\Streams\ReadStream;
use Throwable;

class ParseException extends Exception
{
    public function __construct(ReadStream $stream, string $message, int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message . " at position 0x" . dechex($stream->getPosition()), $code, $previous);
    }
}
