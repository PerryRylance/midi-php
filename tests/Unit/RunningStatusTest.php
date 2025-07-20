<?php

namespace Tests\Unit;

use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Streams\StatusBytes;
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

    fail("Not yet implemented");

});
