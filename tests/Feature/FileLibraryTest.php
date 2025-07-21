<?php

namespace Tests\Feature;

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Exceptions\UnsupportedTrackException;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\File;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;

foreach(glob('./tests/Assets/*.mid') as $path)
{
    it("reads and writes back $path", function() use ($path) {

        $expected = file_get_contents($path);
        $stream = new ReadStream($expected);

        $file = new File();
        $read = fn() => $file->readBytes($stream);

        if(preg_match('/illegal|corrupt/', $path))
        {
            expect($read)->toThrow(ParseException::class);
            return;
        }
        
        if(preg_match('/non-midi-track/', $path))
        {
            expect($read)->toThrow(UnsupportedTrackException::class);
            return;
        }
        
        $read();
        $writeback = function() use ($expected, $file) {

            $stream = new WriteStream();

            $file->writeBytes($stream);

            $actual = $stream->toBinary();

            expect($actual)->toBe($expected);
            
        };

        if(preg_match('/test-2-tracks-type-0/', $path))
            expect($writeback)->toThrow(ValidationException::class);
        else
            $writeback();

    });
}
