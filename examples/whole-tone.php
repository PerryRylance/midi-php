<?php

// NB: This example shows how to load an existing file, manipulate it and save the result

use PerryRylance\Midi\File;
use PerryRylance\Midi\Streams\ReadStream;

require_once 'vendor/autoload.php';

$binary = file_get_contents('examples/input/cascades.mid');

$stream = new ReadStream($binary);
$file = new File();

$file->readBytes($stream);

// TODO: Finish example... there's an invalid control event in here according to the library
