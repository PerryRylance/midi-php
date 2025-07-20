<?php

use Illuminate\Support\Str;
use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Events\Meta\SequenceNumberEvent;
use PerryRylance\Midi\Events\Meta\TrackNameEvent;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\File;
use PerryRylance\Midi\FileType;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Track;

function getCMajorEvents(): array
{
    $events = [];
    $pitches = [60, 62, 64, 65, 67, 69, 71, 72];
    $delta = 480;

    foreach($pitches as $pitch)
    {
        $event = new NoteOnEvent($delta);
        $event->pitch = $pitch;

        $events []= $event;
    }

    return $events;
}

it('fails validation on premature end of track event', function() {

    $track = new Track();
    $stream = new WriteStream();

    $track->events[0] = new EndOfTrackEvent();
    $track->events->merge(getCMajorEvents());

    $track->writeBytes($stream);

})
    ->throws(ValidationException::class);

it('fails validation on missing end of track event', function() {

    $track = new Track();
    $stream = new WriteStream();

    $track->events->merge(getCMajorEvents());

    $track->writeBytes($stream);

})
    ->throws(ValidationException::class);

it('throws when maximum number of tracks exceeded', function() {

    $file = new File();
    $stream = new WriteStream();

    for($i = 0; $i < 0x10000; $i++)
        $file->tracks->append(new Track());

    $file->writeBytes($stream);

})
    ->throws(ValidationException::class);

it('throws serializing type 0 file with more than one track', function() {

    $file = new File();
    $stream = new WriteStream();

    $file->type = FileType::TYPE_0;

    for($i = 0; $i < 2; $i++)
    {
        $track = new Track();
        $track->events->append(new EndOfTrackEvent());

        $file->tracks->append($track);
    }

    $file->writeBytes($stream);

})
    ->throws(ValidationException::class);

it('throws serializing track where copyright event is not on first track', function() {

    $file = new File();
    $stream = new WriteStream();

    for($i = 0; $i < 2; $i++)
    {
        $track = new Track();

        if($i === 1)
            $track->events->append(new CopyrightEvent());

        $track->events->append(new EndOfTrackEvent());

        $file->tracks->append($track);
    }

    $file->writeBytes($stream);

})
    ->throws(ValidationException::class);

foreach([
    CopyrightEvent::class,
    SequenceNumberEvent::class,
    TrackNameEvent::class
] as $class)
{
    $reflect = new ReflectionClass($class);
    $short = $reflect->getShortName();
    $name = Str::of($short)->snake()->replace('_', ' ')->lower();

    it("throws if $name has non-zero absolute time", function() use ($class) {

        $stream = new WriteStream();
        $track = new Track();

        $track->events->merge(getCMajorEvents());

        $instance = new $class();

        $track->events->append($instance);
        $track->events->append(new EndOfTrackEvent());

        $track->writeBytes($stream);

    })
        ->throws(ValidationException::class);
}
