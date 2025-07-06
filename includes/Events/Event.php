<?php

namespace PerryRylance\Midi\Events;

use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Traits\PropertyAccessors;

abstract class Event
{
    abstract public function readBytes(ReadStream $stream): void;
}
