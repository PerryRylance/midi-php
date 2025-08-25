<?php

// NB: This example shows how to load an existing file, manipulate it and save the result

use PerryRylance\Midi\Events\Control\ControlEvent;
use PerryRylance\Midi\Events\Control\NoteOffEvent;
use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\File;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Track;
use PerryRylance\Midi\Events\Control\PitchedEvent;
use PerryRylance\Midi\Streams\WriteStream;

require_once 'vendor/autoload.php';

$binary = file_get_contents('examples/input/cascades.mid');

$in = new ReadStream($binary);
$out = new WriteStream();
$file = new File();

$file->readBytes($in);

$file->tracks->each(fn(Track $track) => $track->events->each(function(Event $event) {

    if(!($event instanceof NoteOnEvent || $event instanceof NoteOffEvent))
        return;

    /** @var PitchedEvent $event */
    $event->pitch = max(0, $event->pitch - ($event->pitch % 2));

}));

$binary = $file->writeBytes($out);

file_put_contents('./examples/output/whole-tone.mid', $binary);
