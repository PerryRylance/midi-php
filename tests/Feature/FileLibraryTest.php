<?php

namespace Tests\Feature;

use Carbon\Exceptions\UnsupportedUnitException;
use Exception;
use LogicException;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Exceptions\UnsupportedTrackException;
use PerryRylance\Midi\Exceptions\ValidationException;
use PerryRylance\Midi\File;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use Illuminate\Support\Str;

foreach(glob('./tests/Fixtures/*.mid') as $path)
{
    if(preg_match('/illegal|corrupt/', $path))
        $expectation = ParseException::class;
    else if(preg_match('/non-midi-track/', $path))
        $expectation = UnsupportedTrackException::class;
    else if(preg_match('/test-2-tracks-type-0/', $path))
        $expectation = ValidationException::class;
    else
        $expectation = true;
    
    if($expectation === true)
        $description = "reads and writes back $path";
    else if(is_subclass_of($expectation, Exception::class))
    {
        $friendly = Str::of( class_basename($expectation) )->headline()->lower();
        $description = "throws $friendly on $path";
    }
    else
        throw new LogicException();

    it($description, function() use ($path, $expectation) {

        $expected = file_get_contents($path);
        $stream = new ReadStream($expected);

        $file = new File();
        $read = fn() => $file->readBytes($stream);

        if($expectation === ParseException::class || $expectation === UnsupportedTrackException::class)
        {
            expect($read)->toThrow($expectation);
            return;
        }
        
        $read();
        $writeback = function() use ($expected, $file) {

            $stream = new WriteStream();

            $file->writeBytes($stream);

            $actual = $stream->toBinary();

            expect($actual)->toBe($expected);
            
        };

        if($expectation === ValidationException::class)
            expect($writeback)->toThrow(ValidationException::class);
        else if($expectation === true)
            $writeback();
        else
            throw new LogicException();

    });
}
