<?php

namespace Tests\Unit;

use PerryRylance\Midi\Resolution;
use PerryRylance\Midi\ResolutionUnits;

it('tests default is 480 PPQ', function() {

    $resolution = new Resolution();

    expect($resolution->units)->toBe(ResolutionUnits::PPQ);
    expect($resolution->ticksPerQuarterNote)->toBe(480);

});
