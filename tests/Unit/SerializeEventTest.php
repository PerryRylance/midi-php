<?php

use PerryRylance\Midi\Events\Meta\CopyrightEvent;
use PerryRylance\Midi\Events\Meta\TextEvent;
use Tests\EventByteArrays;

it('serializes text event', function() {

    $event = new TextEvent();
    $event->text = "Bass";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::TEXT);

});

it('serializes copyright event', function() {

    $event = new CopyrightEvent();
    $event->setText("\xA9 2009 Kaliopa Publishing, LLC", false);

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::COPYRIGHT);

});
