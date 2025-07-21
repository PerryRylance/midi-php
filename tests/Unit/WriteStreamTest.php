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

it('writes byte after seeking back', function() {

    $stream = new WriteStream();

    $stream->writeUint(0x0);
    $stream->seekTo(0);
    $stream->writeByte(0xFF);

    $binary = $stream->toBinary();

    expect($binary)->toBe("\xFF\x00\x00\x00");

});

it('writes signed byte after seeking back', function() {

    $stream = new WriteStream();

    $stream->writeUint(0x0);
    $stream->seekTo(0);
    $stream->writeSignedByte(-1);

    $binary = $stream->toBinary();

    expect($binary)->toBe("\xFF\x00\x00\x00");

});

it('writes short after seeking back', function() {

    $stream = new WriteStream();

    $stream->writeUint(0x0);
    $stream->seekTo(0x1);
    $stream->writeShort(0xFFFF);

    $binary = $stream->toBinary();

    expect($binary)->toBe("\x00\xFF\xFF\x00");

});

it('writes uint after seeking back', function() {

    $stream = new WriteStream();

    $stream->writeUint(0x0);
    $stream->writeUint(0x0);

    $stream->seekTo(0x2);
    $stream->writeUint(0x12345678);

    $binary = $stream->toBinary();

    expect($binary)->toBe("\x00\x00\x12\x34\x56\x78\x00\x00");

});

it('throws seeking beyond end', function() {

    $stream = new WriteStream();

    $stream->writeUint(0x0);
    $stream->seekTo(0x5);

})
    ->throws(OutOfRangeException::class);
