<?php

namespace Tests\Unit;

use InvalidArgumentException;
use OutOfRangeException;
use PerryRylance\Midi\Events\Meta\FrameRate;
use PerryRylance\Midi\Exceptions\ResolutionException;
use PerryRylance\Midi\Resolution;
use PerryRylance\Midi\ResolutionUnits;

it('tests default is 480 PPQ', function() {

    $resolution = new Resolution();

    expect($resolution->units)->toBe(ResolutionUnits::PPQ);
    expect($resolution->ticksPerQuarterNote)->toBe(480);

});

it('tests PPQ ticks cannot be zero', function() {

    $resolution = new Resolution();
    $resolution->ticksPerQuarterNote = 0;    

})
    ->throws(OutOfRangeException::class);

it('tests PPQ ticks cannot be greater than 0x7FFF', function() {

    $resolution = new Resolution();
    $resolution->ticksPerQuarterNote = 0x8001;

})
    ->throws(OutOfRangeException::class);

it('tests PPQ ticks cannot be floating point', function() {

    $resolution = new Resolution();
    $resolution->ticksPerQuarterNote = 123.456;

})
    ->throws(InvalidArgumentException::class);

it('tests FPS can be valid frame rates', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 100);

    expect($resolution->units)->toBe(ResolutionUnits::FPS);
    expect($resolution->ticksPerFrame)->toBe(100);

});

it('throws on zero ticks per frame', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 0);

})
    ->throws(OutOfRangeException::class);

it('throws on ticks per frame greater than 0xFF', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 0x100);

})
    ->throws(OutOfRangeException::class);

it('throws on floating point ticks per frame', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 12.34);

})
    ->throws(InvalidArgumentException::class);

it('throws getting PPQ from FPS resolution', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 100);

    $resolution->ticksPerQuarterNote;

})
    ->throws(ResolutionException::class);

it('throws getting FPS from PPQ resolution', function() {

    $resolution = new Resolution();

    $resolution->framesPerSecond;

})
    ->throws(ResolutionException::class);

it('throw getting ticks per frame from PPQ resolution', function() {

    $resolution = new Resolution();

    $resolution->ticksPerFrame;

})
    ->throws(ResolutionException::class);

it('can set frames per second independently', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 100);

    $resolution->framesPerSecond = FrameRate::FPS_DROP_30;

    expect($resolution->framesPerSecond)->toBe(FrameRate::FPS_DROP_30);
    expect($resolution->ticksPerFrame)->toBe(100);

});

it('can set ticks per frame independently', function() {

    $resolution = new Resolution();
    $resolution->setFps(FrameRate::FPS_24, 100);

    $resolution->ticksPerFrame = 50;

    expect($resolution->framesPerSecond)->toBe(FrameRate::FPS_24);
    expect($resolution->ticksPerFrame)->toBe(50);

});
