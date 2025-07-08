<?php

namespace Tests\Unit;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\CuePointEvent;
use PerryRylance\Midi\Events\Meta\InstrumentNameEvent;
use PerryRylance\Midi\Events\Meta\MarkerEvent;
use PerryRylance\Midi\Events\Meta\TextEvent;
use PerryRylance\Midi\Events\Meta\TrackNameEvent;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;
use Tests\EventByteArrays;

/**
 * @template TEvent of Event
 * @param array<int> $bytes
 * @return TEvent
 */
function getEventFromByteArray(array $bytes): Event
{
    $binary = pack('C*', ...$bytes);
    $stream = new ReadStream($binary);

    return EventFactory::fromStream($stream, new StatusBytes);
}

it("reads text event", function() {

    /** @var TextEvent $event */
    $event = getEventFromByteArray(EventByteArrays::TEXT);

    expect($event)->toBeInstanceOf(TextEvent::class);
    expect($event->text)->toBe("Bass");

});

it("throws on bad text event", function() {

    getEventFromByteArray(EventByteArrays::INVALID_TEXT);

})
    ->throws(ParseException::class);

it("reads copyright event", function() {

    /** @var CopyrightEvent $event */
    $event = getEventFromByteArray(EventByteArrays::COPYRIGHT);

    expect($event)->toBeInstanceOf(CopyrightEvent::class);
    expect($event->text)->toBe("\xA9 2009 Kaliopa Publishing, LLC");

});

it("reads track name event", function() {

    /** @var TrackNameEvent $event */
    $event = getEventFromByteArray(EventByteArrays::TRACK_NAME);

    expect($event)->toBeInstanceOf(TrackNameEvent::class);
    expect($event->text)->toBe("Bass");

});

it("reads instrument name event", function() {

    /** @var InstrumentNameEvent $event */
    $event = getEventFromByteArray(EventByteArrays::INSTRUMENT_NAME);

    expect($event)->toBeInstanceOf(InstrumentNameEvent::class);
    expect($event->text)->toBe("Bass");

});

it("reads marker event", function() {

    /** @var MarkerEvent $event */
    $event = getEventFromByteArray(EventByteArrays::MARKER);

    expect($event)->toBeInstanceOf(MarkerEvent::class);
    expect($event->text)->toBe('Verse');

});

it("reads cue point event", function() {

    /** @var CuePointEvent $event */
    $event = getEventFromByteArray(EventByteArrays::CUE_POINT);

    expect($event)->toBeInstanceOf(CuePointEvent::class);
    expect($event->text)->toBe("Solo");

});

// it("reads set tempo event", function() {

//     /** @var SetTempoEvent $event */
//     $event = getEventFromByteArray()

// });
