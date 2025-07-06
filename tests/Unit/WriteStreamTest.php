<?php

use PerryRylance\Midi\Streams\WriteStream;

it('writes a byte', function() {

    $byte = 0x7F;
    $stream = new WriteStream();

    $stream->writeByte($byte);

    $buffer = $stream->toBinary();
    $readback = unpack('C', $buffer)[1];
    
    expect($readback)->toBe(0x7F);

});

it('writes a short', function() {



});

it('writes a uint', function() {



});

it('writes a vlv', function() {



});
