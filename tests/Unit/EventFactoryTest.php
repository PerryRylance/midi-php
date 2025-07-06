<?php

namespace Tests\Unit;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;

function getEventFromByteArray(array $bytes): Event
{
    $binary = pack('C*', ...$bytes);
    $stream = new ReadStream($binary);

    return EventFactory::fromStream($stream, new StatusBytes);
}

it("reads text event", function() {

    

});
