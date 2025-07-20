<?php

use PerryRylance\Midi\Events\Meta\TextEvent;
use Tests\EventByteArrays;

it('serializes text event', function() {

    $event = new TextEvent();
    $event->text = "Bass";

    expect($event)->toMatchByteArrayWhenSerialized(EventByteArrays::TEXT);

});
