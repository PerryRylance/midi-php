<?php

use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;

it('reads a signed byte', function () {

    $buffer = "\x7F";
    $stream = new ReadStream($buffer);
    $readback = $stream->readByte();

    expect($readback)->toBe(0x7F);

});

it('reads an unsigned byte', function() {

    $buffer = "\x00";
    $stream = new ReadStream($buffer);
    $readback = $stream->readByte();

    expect($readback)->toBe(0x0);

});

it('reads a short', function() {

    $buffer = "\x12\x34";
    $stream = new ReadStream($buffer);
    $readback = $stream->readShort();

    expect($readback)->toBe(0x1234);

});

it('reads a uint', function() {

    $buffer = "\xFE\xED\xBE\xEF";
    $stream = new ReadStream($buffer);
    $readback = $stream->readUint();

    expect($readback)->toBe(0xFEEDBEEF);

});

it('reads a vlv', function() {

    $buffer = "\x82\x80\x00";
    $stream = new ReadStream($buffer);
    $readback = $stream->readVlv();

    expect($readback)->toBe(32768);

});

it('throws on unexpected end', function() {

    $stream = new ReadStream("");
    $stream->readByte();

})
    ->throws(ParseException::class);
