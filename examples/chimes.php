<?php

// NB: This example shows how to generate a file from scratch

use PerryRylance\Midi\Events\Control\NoteOffEvent;
use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Control\ProgramChangeEvent;
use PerryRylance\Midi\Events\Control\ProgramType;
use PerryRylance\Midi\File;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Track;

require_once 'vendor/autoload.php';

$file = new File();
$track = new Track();
$stream = new WriteStream();
$random = new Random\Randomizer();
$ticksPerQuarterNote = $file->resolution->ticksPerQuarterNote;

$program = new ProgramChangeEvent();
$program->program = ProgramType::TUBULAR_BELLS;

$track->events->append($program);

for($i = 0; $i < 100; $i++)
{
    $duration = $random->getInt($ticksPerQuarterNote, 8 * $ticksPerQuarterNote);
    $pitch = $random->getInt(48, 60);

    $on = new NoteOnEvent();
    $on->pitch = $pitch;

    $off = new NoteOffEvent();
    $off->pitch = $pitch;
    $off->delta = $duration;

    $track->events->append($on);
    $track->events->append($off);
}

$file->tracks->append($track);
$file->writeBytes($stream);

file_put_contents('examples/output/chimes.mid', $stream->toBinary());
