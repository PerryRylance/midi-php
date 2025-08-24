<?php

namespace Tests\Feature;

use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Track;
use PerryRylance\Midi\TrackSerializationOptions;

it('serializes basic track', function() {

    $stream = new WriteStream();
    $track = new Track();

    $track->events->append(new EndOfTrackEvent());

    $track->writeBytes($stream);

    $binary = $stream->toBinary();

    $expected = "\x4D\x54\x72\x6B\x00\x00\x00\x04\x00\xFF\x2F\x00";

    expect($binary)->toBe($expected);

});

it('omits premature end of track with automatic end of track flag', function() {

    $stream = new WriteStream();
    $track = new Track();

    $track->events->append(new EndOfTrackEvent());
    $track->events->append(new NoteOnEvent());

    $track->writeBytes($stream, TrackSerializationOptions::AUTOMATIC_END_OF_TRACK);

    $binary = $stream->toBinary();
    $trimmed = substr($binary, 0, -4); // NB: Trim off the track end event, we aren't testing that here

    $expected = "\x4D\x54\x72\x6B\x00\x00\x00\x08\x00\x90\x3C\x7F";

    expect($trimmed)->toBe($expected);

});

it('appends missing end of track with automatic end of track flag', function() {

    $stream = new WriteStream();
    $track = new Track();

    $track->writeBytes($stream, TrackSerializationOptions::AUTOMATIC_END_OF_TRACK);

    $binary = $stream->toBinary();

    $expected = "\x4D\x54\x72\x6B\x00\x00\x00\x04\x00\xFF\x2F\x00";

    expect($binary)->toBe($expected);

});
