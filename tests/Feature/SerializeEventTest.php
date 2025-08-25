<?php

namespace Tests\Feature;

use PerryRylance\Midi\Events\Control\AftertouchEvent;
use PerryRylance\Midi\Events\Control\ChannelAftertouchEvent;
use PerryRylance\Midi\Events\Control\ControllerEvent;
use PerryRylance\Midi\Events\Control\ControllerType;
use PerryRylance\Midi\Events\Control\NoteOffEvent;
use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Control\PitchWheelEvent;
use PerryRylance\Midi\Events\Control\ProgramChangeEvent;
use PerryRylance\Midi\Events\Control\ProgramType;
use PerryRylance\Midi\Events\Meta\ChannelPrefixEvent;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\CuePointEvent;
use PerryRylance\Midi\Events\Meta\DeviceManufacturer;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Events\Meta\InstrumentNameEvent;
use PerryRylance\Midi\Events\Meta\LyricEvent;
use PerryRylance\Midi\Events\Meta\MarkerEvent;
use PerryRylance\Midi\Events\Meta\SetTempoEvent;
use PerryRylance\Midi\Events\Meta\SmtpeOffsetEvent;
use PerryRylance\Midi\Events\Meta\TextEvent;
use PerryRylance\Midi\Events\Meta\TrackNameEvent;
use PerryRylance\Midi\Events\Meta\FrameRate;
use PerryRylance\Midi\Events\Meta\KeySignatureEvent;
use PerryRylance\Midi\Events\Meta\PortPrefixEvent;
use PerryRylance\Midi\Events\Meta\Quality;
use PerryRylance\Midi\Events\Meta\SequenceNumberEvent;
use PerryRylance\Midi\Events\Meta\SequencerSpecificEvent;
use PerryRylance\Midi\Events\Meta\TimeSignatureEvent;
use PerryRylance\Midi\Events\SysEx\SysExEvent;
use Tests\EventByteArrays;

it('serializes text event', function() {

    $event = new TextEvent();
    $event->text = "Bass";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::TEXT);

});

it('serializes long text event with vlv', function() {

    $event = new TextEvent();
    $event->text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a est eu elit pellentesque volutpat. In commodo odio vel justo dapibus, sed blandit orci convallis. Duis tristique posuere ligula, sit amet volutpat augue. Integer blandit felis at magna consectetur gravida. Donec finibus sapien mi.";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::LONG_TEXT);

});

it('serializes copyright event', function() {

    $event = new CopyrightEvent();
    $event->setText("\xA9 2009 Kaliopa Publishing, LLC", false);

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::COPYRIGHT);

});

it('serializes track name event', function() {

    $event = new TrackNameEvent();
    $event->text = "Bass";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::TRACK_NAME);

});

it('serializes instrument name event', function() {

    $event = new InstrumentNameEvent();
    $event->text = "Bass";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::INSTRUMENT_NAME);

});

it('serializes lyric event', function () {
    $event = new LyricEvent();
    $event->text = 'la-';

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::LYRIC);
});

it('serializes marker event', function () {
    $event = new MarkerEvent();
    $event->text = 'Verse';

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::MARKER);
});

it('serializes cue point event', function () {
    $event = new CuePointEvent();
    $event->text = 'Solo';

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::CUE_POINT);
});

it('serializes set tempo event', function () {
    $event = new SetTempoEvent();
    $event->bpm = 120;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::SET_TEMPO);
});

it('serializes smtpe offset event', function () {
    $event = new SmtpeOffsetEvent();
    $event->rate = FrameRate::FPS_24;
    $event->hours = 1;
    $event->minutes = 0;
    $event->seconds = 0;
    $event->frames = 0;
    $event->subframes = 0;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::SMTPE_OFFSET);
});

it('serializes sequence number event', function () {
    $event = new SequenceNumberEvent();
    $event->number = 2;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::SEQUENCE_NUMBER);
});

it('serializes end of track event', function () {
    $event = new EndOfTrackEvent();

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::END_OF_TRACK);
});

it('serializes channel prefix event', function () {
    $event = new ChannelPrefixEvent();
    $event->channel = 2;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::CHANNEL_PREFIX);
});

it('serializes port prefix event', function () {
    $event = new PortPrefixEvent();
    $event->port = 3;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::PORT_PREFIX);
});

it('serializes key signature event', function () {
    $event = new KeySignatureEvent();
    $event->accidentals = 4;
    $event->quality = Quality::MAJOR;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::KEY_SIGNATURE);
});

it('serializes time signature event', function () {
    $event = new TimeSignatureEvent();
    $event->numerator = 4;
    $event->denominator = 4;
    $event->ticksPerMetronomeClick = 24;
    $event->num32ndNotesPerBeat = 8;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::TIME_SIGNATURE);
});

it('serializes sequencer specific event', function () {
    $event = new SequencerSpecificEvent();
    $event->manufacturer = DeviceManufacturer::ROLAND;
    $event->bytes = "\x04\x01\x56";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::SEQUENCER_SPECIFIC);
});

it('serializes sysex event', function () {
    $event = new SysExEvent();
    $event->manufacturer = DeviceManufacturer::ROLAND;
    $event->bytes = "\x01\x34";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::SYSEX);
});

it('serializes note on event', function () {
    $event = new NoteOnEvent();
    $event->channel = 2;
    $event->pitch = 61;
    $event->velocity = 120;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::NOTE_ON);
});

it('serializes note off event', function () {
    $event = new NoteOffEvent();
    $event->channel = 3;
    $event->pitch = 62;
    $event->velocity = 120;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::NOTE_OFF);
});

it('serializes aftertouch event', function () {
    $event = new AftertouchEvent();
    $event->channel = 4;
    $event->pitch = 63;
    $event->pressure = 121;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::AFTERTOUCH);
});

it('serializes controller event', function () {
    $event = new ControllerEvent();
    $event->channel = 6;
    $event->controller = ControllerType::CHANNEL_VOLUME_COARSE;
    $event->value = 16;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::CONTROLLER);
});

it('serializes program change event', function () {
    $event = new ProgramChangeEvent();
    $event->channel = 6;
    $event->program = ProgramType::CLAVINET;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::PROGRAM_CHANGE);
});

it('serializes channel aftertouch event', function () {
    $event = new ChannelAftertouchEvent();
    $event->channel = 6;
    $event->pressure = 53;

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::CHANNEL_AFTERTOUCH);
});

it('serializes pitch wheel event', function () {

    // NB: Yes it's a readback test, but we don't expose "value" so it serves it's purpose
    /** @var PitchWheelEvent $event */
    $event = getEventFromByteArray(EventByteArrays::PITCH_WHEEL);

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::PITCH_WHEEL);

    $event->amount = -1;
    expect($event->value)->toBe(0x0);

    $event->amount = 0;
    expect($event->value)->toBe(0x2000);

    $event->amount = 1;
    expect($event->value)->toBe(0x3FFF);
});
