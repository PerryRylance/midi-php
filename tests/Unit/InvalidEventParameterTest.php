<?php

use PerryRylance\Midi\Events\Meta\{
    ChannelPrefixEvent,
    CopyrightEvent,
    CuePointEvent,
    InstrumentNameEvent,
    KeySignatureEvent,
    LyricEvent,
    MarkerEvent,
    TextEvent,
    TimeSignatureEvent,
    TrackNameEvent
};

use PerryRylance\Midi\Events\Control\{
    AftertouchEvent,
    ChannelAftertouchEvent,
    NoteOnEvent,
    NoteOffEvent,
    ControllerEvent,
    ProgramChangeEvent,
    PitchWheelEvent
};

$TEXT_EVENT_CLASSES = [
    TextEvent::class,
    CopyrightEvent::class,
    TrackNameEvent::class,
    InstrumentNameEvent::class,
    LyricEvent::class,
    MarkerEvent::class,
    CuePointEvent::class,
];

it('tests negative event delta throws range error', function () {
    $event = new TextEvent();
    $event->delta = -1;
})->throws(OutOfRangeException::class);

it('tests floating point event delta throws error', function () {
    $event = new TextEvent();
    $event->delta = pi();
})->throws(InvalidArgumentException::class);

it('tests delta VLV larger than 4 bytes throws error', function () {
    $event = new TextEvent();
    $event->delta = 0xFFFFFFFF;
})->throws(OutOfRangeException::class);

it('tests channel prefix negative channel throws range error', function () {
    $event = new ChannelPrefixEvent();
    $event->channel = -1;
})->throws(OutOfRangeException::class);

it('tests channel prefix channel too high throws range error', function () {
    $event = new ChannelPrefixEvent();
    $event->channel = 255;
})->throws(OutOfRangeException::class);

it('tests text events text cannot exceed length 255', function () use ($TEXT_EVENT_CLASSES) {
    $tooLongText = str_repeat("a", 256);

    foreach ($TEXT_EVENT_CLASSES as $class) {
        $event = new $class();
        $event->text = $tooLongText;
    }
})->throws(OverflowException::class);

it('tests text events text cannot contain non-ASCII characters', function () {
    $event = new TextEvent();
    $event->text = "🦓";
})->throws(InvalidArgumentException::class);

it('tests key signature cannot have accidentals too low', function () {
    $event = new KeySignatureEvent();
    $event->accidentals = -8;
})->throws(OutOfRangeException::class);

it('tests key signature cannot have accidentals too high', function () {
    $event = new KeySignatureEvent();
    $event->accidentals = 8;
})->throws(OutOfRangeException::class);

it('tests time signature must have positive, non-zero numerator (0)', function () {
    $event = new TimeSignatureEvent();
    $event->numerator = 0;
})->throws(OutOfRangeException::class);

it('tests time signature must have positive, non-zero numerator (too high)', function () {
    $event = new TimeSignatureEvent();
    $event->numerator = 256;
})->throws(OutOfRangeException::class);

it('tests time signature cannot have invalid denominator', function () {
    $event = new TimeSignatureEvent();
    $event->denominator = 3;
})->throws(InvalidArgumentException::class);

it('tests time signature cannot have zero ticks per metronome click', function () {
    $event = new TimeSignatureEvent();
    $event->ticksPerMetronomeClick = 0;
})->throws(OutOfRangeException::class);

it('tests time signature cannot have 256 ticks per metronome click', function () {
    $event = new TimeSignatureEvent();
    $event->ticksPerMetronomeClick = 256;
})->throws(OutOfRangeException::class);

it('tests time signature cannot have zero 32nd notes per beat', function () {
    $event = new TimeSignatureEvent();
    $event->num32ndNotesPerBeat = 0;
})->throws(OutOfRangeException::class);

it('tests time signature cannot have 256 32nd notes per beat', function () {
    $event = new TimeSignatureEvent();
    $event->num32ndNotesPerBeat = 256;
})->throws(OutOfRangeException::class);

// Control Events — delta + channel
foreach ([
    AftertouchEvent::class,
    ChannelAftertouchEvent::class,
    NoteOnEvent::class,
    NoteOffEvent::class,
    ControllerEvent::class,
    PitchWheelEvent::class,
    ProgramChangeEvent::class
] as $class) {
    $name = (new ReflectionClass($class))->getShortName();

    it("{$name} cannot have negative delta", function () use ($class) {
        $instance = new $class();
        $instance->delta = -1;
    })->throws(OutOfRangeException::class);

    it("{$name} cannot have floating point delta", function () use ($class) {
        $instance = new $class();
        $instance->delta = 1.234;
    })->throws(InvalidArgumentException::class);

    it("{$name} cannot have delta above 0x0FFFFFFF", function () use ($class) {
        $instance = new $class();
        $instance->delta = 0xFFFFFFFF;
    })->throws(OutOfRangeException::class);

    it("{$name} cannot have negative channel", function () use ($class) {
        $instance = new $class();
        $instance->channel = -1;
    })->throws(OutOfRangeException::class);

    it("{$name} cannot have floating point channel", function () use ($class) {
        $instance = new $class();
        $instance->channel = 1.234;
    })->throws(InvalidArgumentException::class);

    it("{$name} cannot have channel above 0x10", function () use ($class) {
        $instance = new $class();
        $instance->channel = 0x10;
    })->throws(OutOfRangeException::class);
}

// Events with pitch
foreach ([AftertouchEvent::class, NoteOnEvent::class, NoteOffEvent::class] as $class) {
    $name = (new ReflectionClass($class))->getShortName();

    it("{$name} cannot have negative pitch", function () use ($class) {
        $instance = new $class();
        $instance->pitch = -1;
    })->throws(OutOfRangeException::class);

    it("{$name} cannot have floating point pitch", function () use ($class) {
        $instance = new $class();
        $instance->pitch = 60.123;
    })->throws(InvalidArgumentException::class);

    it("{$name} cannot have pitch above 0xFF", function () use ($class) {
        $instance = new $class();
        $instance->pitch = 128;
    })->throws(OutOfRangeException::class);
}

// Events with velocity
foreach ([NoteOnEvent::class, NoteOffEvent::class] as $class) {
    $name = (new ReflectionClass($class))->getShortName();

    it("{$name} cannot have negative velocity", function () use ($class) {
        $instance = new $class();
        $instance->velocity = -1;
    })->throws(OutOfRangeException::class);

    it("{$name} cannot have floating point velocity", function () use ($class) {
        $instance = new $class();
        $instance->velocity = 60.123;
    })->throws(InvalidArgumentException::class);

    it("{$name} cannot have velocity above 0xFF", function () use ($class) {
        $instance = new $class();
        $instance->velocity = 128;
    })->throws(OutOfRangeException::class);
}

// Events with pressure
foreach ([AftertouchEvent::class, ChannelAftertouchEvent::class] as $class) {
    $name = (new ReflectionClass($class))->getShortName();

    it("{$name} cannot have negative pressure", function () use ($class) {
        $instance = new $class();
        $instance->pressure = -1;
    })->throws(OutOfRangeException::class);

    it("{$name} cannot have floating point pressure", function () use ($class) {
        $instance = new $class();
        $instance->pressure = 60.123;
    })->throws(InvalidArgumentException::class);

    it("{$name} cannot have pressure above 0xFF", function () use ($class) {
        $instance = new $class();
        $instance->pressure = 128;
    })->throws(OutOfRangeException::class);
}

// Pitch wheel
it('tests pitch wheel cannot have amount less than negative one', function () {
    $event = new PitchWheelEvent();
    $event->amount = -1.1;
})->throws(OutOfRangeException::class);

it('tests pitch wheel cannot have amount more than positive one', function () {
    $event = new PitchWheelEvent();
    $event->amount = 1.1;
})->throws(OutOfRangeException::class);
