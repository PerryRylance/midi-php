<?php

namespace Tests\Unit;

use PerryRylance\Midi\File;
use PerryRylance\Midi\Streams\ReadStream;

foreach(glob('./tests/Assets/*.mid') as $file)
{
    it("reads and writes back $file", function() use ($file) {

        $binary = file_get_contents($file);
        $stream = new ReadStream($binary);

        $file = new File();
        $file->readBytes($stream);

    });

    break;
}
