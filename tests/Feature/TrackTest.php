<?php

namespace Tests\Feature;

use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Track;

it('serializes basic track', function() {

    $stream = new WriteStream();
    $track = new Track();

    $track->events->append(new EndOfTrackEvent());

    $track->writeBytes($stream);

    $binary = $stream->toBinary();

    $expected = "\x4D\x54\x72\x6B\x00\x00\x00\x04\x00\xFF\x2F\x00";

    expect($binary)->toBe($expected);

});
