<?php

use PerryRylance\Midi\Streams\Stream;
use PerryRylance\Midi\Streams\WriteStream;

it('writes a byte', function() {

    $byte = 0x7F;
    $stream = new WriteStream();

    $stream->writeByte($byte);

    $buffer = $stream->toBinary();
    $readback = unpack(Stream::FORMAT_BYTE, $buffer)[1];

    expect($readback)->toBe($byte);

});

it('writes a short', function() {

    $short = 0x7FFF;
    $stream = new WriteStream();

    $stream->writeShort($short);

    $buffer = $stream->toBinary();
    $readback = unpack(Stream::FORMAT_SHORT, $buffer)[1];

    expect($readback)->toBe($short);

});

it('writes a uint', function() {

    $uint = 0xFEEDBEEF;
    $stream = new WriteStream();

    $stream->writeUint($uint);

    $buffer = $stream->toBinary();
    $readback = unpack(Stream::FORMAT_UINT, $buffer)[1];

    expect($readback)->toBe($uint);

});

it('writes a vlv', function() {

    $stream = new WriteStream();
    $stream->writeVlv(32768);

    $buffer = $stream->toBinary();

    $readback = 0;

    for($i = 0; $i < 3; $i++)
        $readback = ($readback << 8) | unpack(Stream::FORMAT_BYTE, $buffer, $i)[1];

    expect($readback)->toBe(0x828000);

});
