<?php

namespace Tests\Unit;

use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Track;
use Tests\EventByteArrays;

it('parses C major triad with running status', function() {

    $stream = getReadStreamFromByteArray(EventByteArrays::RUNNING_C_MAJOR_TRIAD);
    $status = new StatusBytes;

    /** @var NoteOnEvent $c */
    [$c, $e, $g] = array_map(fn() => EventFactory::fromStream($stream, $status, $stream->readVLV()), [0, 1, 2]);

    expect($c)->toBeInstanceOf(NoteOnEvent::class);
    expect($c->channel)->toBe(0);
    expect($c->pitch)->toBe(60);
    expect($c->velocity)->toBe(127);

    expect($e)->toBeInstanceOf(NoteOnEvent::class);
    expect($e->channel)->toBe(0);
    expect($e->pitch)->toBe(64);
    expect($e->velocity)->toBe(127);

    expect($g)->toBeInstanceOf(NoteOnEvent::class);
    expect($g->channel)->toBe(0);
    expect($g->pitch)->toBe(67);
    expect($g->velocity)->toBe(127);

});

it('serializes C major triad with running status', function() {

    $track = new Track();
    $stream = new WriteStream();

    foreach([60, 64, 67] as $pitch)
    {
        $event = new NoteOnEvent();

        $event->pitch = $pitch;
        $event->velocity = 127;

        $track->events []= $event;
    }

    // NB: End of track event, to pass validation. Running status is a concept of Track, so this makes sense to have in the test
    $track->events []= new EndOfTrackEvent();

    $track->writeBytes($stream);

    $actual = $stream->toBinary();

    // NB: Slice off MTrk and chunk size
    $actual = substr($actual, 8);

    $expected = EventByteArrays::toBinary(EventByteArrays::RUNNING_C_MAJOR_TRIAD);

    

    expect($actual)->toBe($expected);

});
