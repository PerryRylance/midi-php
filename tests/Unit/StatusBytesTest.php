<?php

namespace Tests\Unit;

use BadMethodCallException;
use PerryRylance\Midi\Streams\StatusBytes;
use RangeException;

it('is initially zeroed', function() {

    $status = new StatusBytes;

    $this->assertSame(0, $status[0]);
    $this->assertSame(0, $status[1]);

});

it('can set valid status bytes', function() {

    $this->expectNotToPerformAssertions();

    $status = new StatusBytes;

    $status[0] = 0x1;
    $status[1] = 0xFF;

});

it('throws attempting to resize status bytes', function() {

    $status = new StatusBytes;
    $status->setSize(3);

})
    ->throws(BadMethodCallException::class);

it('throws attempting to set out of range value in status bytes', function() {

    $status = new StatusBytes;

    $status[0] = 0xFFF;

})
    ->throws(RangeException::class);
