<?php

namespace Tests\Unit;

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Events\Meta\ChannelPrefixEvent;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\CuePointEvent;
use PerryRylance\Midi\Events\Meta\DeviceManufacturer;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Events\Meta\FrameRate;
use PerryRylance\Midi\Events\Meta\InstrumentNameEvent;
use PerryRylance\Midi\Events\Meta\KeySignatureEvent;
use PerryRylance\Midi\Events\Meta\MarkerEvent;
use PerryRylance\Midi\Events\Meta\PortPrefixEvent;
use PerryRylance\Midi\Events\Meta\Quality;
use PerryRylance\Midi\Events\Meta\SequenceNumberEvent;
use PerryRylance\Midi\Events\Meta\SequencerSpecificEvent;
use PerryRylance\Midi\Events\Meta\SetTempoEvent;
use PerryRylance\Midi\Events\Meta\SmtpeOffsetEvent;
use PerryRylance\Midi\Events\Meta\TextEvent;
use PerryRylance\Midi\Events\Meta\TimeSignatureEvent;
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

it("reads set tempo event", function() {

    /** @var SetTempoEvent $event */
    $event = getEventFromByteArray(EventByteArrays::SET_TEMPO);

    expect($event)->toBeInstanceOf(SetTempoEvent::class);
    expect($event->bpm)->toBe(120);

});

it("reads smtpe offset event", function() {

    /** @var SmtpeOffsetEvent $event */
    $event = getEventFromByteArray(EventByteArrays::SMTPE_OFFSET);

    expect($event)->toBeInstanceOf(SmtpeOffsetEvent::class);
    expect($event->rate)->toBe(FrameRate::FPS_24);
    expect($event->hours)->toBe(1);
    expect($event->minutes)->toBe(0);
    expect($event->seconds)->toBe(0);
    expect($event->frames)->toBe(0);
    expect($event->subframes)->toBe(0);

});

it("reads sequence number event", function() {

    /** @var SequenceNumberEvent $event */
    $event = getEventFromByteArray(EventByteArrays::SEQUENCE_NUMBER);

    expect($event)->toBeInstanceOf(SequenceNumberEvent::class);

    expect($event->number)->toBe(2);

});

it("reads end of track event", function() {

    /** @var EndOfTrackEvent $event */
    $event = getEventFromByteArray(EventByteArrays::END_OF_TRACK);

    expect($event)->toBeInstanceOf(EndOfTrackEvent::class);

});

it("reads channel prefix event", function () {
    /** @var ChannelPrefixEvent $event */
    $event = getEventFromByteArray(EventByteArrays::CHANNEL_PREFIX);

    expect($event)->toBeInstanceOf(ChannelPrefixEvent::class);
    expect($event->channel)->toBe(2);
});

it("reads port prefix event", function () {
    /** @var PortPrefixEvent $event */
    $event = getEventFromByteArray(EventByteArrays::PORT_PREFIX);

    expect($event)->toBeInstanceOf(PortPrefixEvent::class);
    expect($event->port)->toBe(3);
});

it("reads key signature event", function () {
    /** @var KeySignatureEvent $event */
    $event = getEventFromByteArray(EventByteArrays::KEY_SIGNATURE);

    expect($event)->toBeInstanceOf(KeySignatureEvent::class);
    expect($event->accidentals)->toBe(4);
    expect($event->quality)->toBe(Quality::MAJOR);
});

it("reads time signature event", function () {
    /** @var TimeSignatureEvent $event */
    $event = getEventFromByteArray(EventByteArrays::TIME_SIGNATURE);

    expect($event)->toBeInstanceOf(TimeSignatureEvent::class);
    expect($event->numerator)->toBe(4);
    expect($event->denominator)->toBe(4);
    expect($event->ticksPerMetronomeClick)->toBe(24);
    expect($event->num32ndNotesPerBeat)->toBe(8);
});

it("reads sequencer specific event", function () {
    /** @var SequencerSpecificEvent $event */
    $event = getEventFromByteArray(EventByteArrays::SEQUENCER_SPECIFIC);

    expect($event)->toBeInstanceOf(SequencerSpecificEvent::class);
    expect($event->manufacturer)->toBe(DeviceManufacturer::ROLAND);
    expect(strlen($event->bytes))->toBe(3);
});

it("throws on invalid meta event type", function () {

    getEventFromByteArray(EventByteArrays::INVALID_META_EVENT_TYPE);

})
    ->throws(ParseException::class);

it("reads sysex event", function () {
    /** @var SysExEvent $event */
    $event = getEventFromByteArray(EventByteArrays::SYSEX);

    expect($event)->toBeInstanceOf(SysExEvent::class);
    expect($event->manufacturer)->toBe(DeviceManufacturer::ROLAND);
    expect(count($event->bytes))->toBe(2);
});

it("reads note on event", function () {
    /** @var NoteOnEvent $event */
    $event = getEventFromByteArray(EventByteArrays::NOTE_ON);

    expect($event)->toBeInstanceOf(NoteOnEvent::class);
    expect($event->channel)->toBe(2);
    expect($event->key)->toBe(61);
    expect($event->velocity)->toBe(120);
});

it("reads note off event", function () {
    /** @var NoteOffEvent $event */
    $event = getEventFromByteArray(EventByteArrays::NOTE_OFF);

    expect($event)->toBeInstanceOf(NoteOffEvent::class);
    expect($event->channel)->toBe(3);
    expect($event->key)->toBe(62);
    expect($event->velocity)->toBe(120);
});

it("reads aftertouch event", function () {
    /** @var AftertouchEvent $event */
    $event = getEventFromByteArray(EventByteArrays::AFTERTOUCH);

    expect($event)->toBeInstanceOf(AftertouchEvent::class);
    expect($event->channel)->toBe(4);
    expect($event->key)->toBe(63);
    expect($event->pressure)->toBe(121);
});

it("reads controller event", function () {
    /** @var ControllerEvent $event */
    $event = getEventFromByteArray(EventByteArrays::CONTROLLER);

    expect($event)->toBeInstanceOf(ControllerEvent::class);
    expect($event->channel)->toBe(6);
    expect($event->controller)->toBe(ControllerType::CHANNEL_VOLUME_COARSE);
    expect($event->value)->toBe(16);
});

it("reads program change event", function () {
    /** @var ProgramChangeEvent $event */
    $event = getEventFromByteArray(EventByteArrays::PROGRAM_CHANGE);

    expect($event)->toBeInstanceOf(ProgramChangeEvent::class);
    expect($event->channel)->toBe(6);
    expect($event->program)->toBe(ProgramType::CLAVINET);
});

it("reads channel aftertouch event", function () {
    /** @var ChannelAftertouchEvent $event */
    $event = getEventFromByteArray(EventByteArrays::CHANNEL_AFTERTOUCH);

    expect($event)->toBeInstanceOf(ChannelAftertouchEvent::class);
    expect($event->channel)->toBe(6);
    expect($event->pressure)->toBe(53);
});

it("reads pitch wheel event", function () {
    /** @var PitchWheelEvent $event */
    $event = getEventFromByteArray(EventByteArrays::PITCH_WHEEL);

    expect($event)->toBeInstanceOf(PitchWheelEvent::class);
    expect($event->channel)->toBe(3);
    expect($event->value)->toBe(0x1CD4);
});
