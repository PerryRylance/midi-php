<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;
use Tests\EventByteArrays;

pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getReadStreamFromByteArray(array $bytes): ReadStream
{
    $binary = pack('C*', ...$bytes);
    $stream = new ReadStream($binary);

    return $stream;
}

/**
 * @template TEvent of Event
 * @param array<int> $bytes
 * @return TEvent
 */
function getEventFromByteArray(array $bytes): Event
{
    return EventFactory::fromStream(getReadStreamFromByteArray($bytes), new StatusBytes);
}

expect()->extend('toMatchByteArrayWhenSerialized', function(array $bytes) {

    $stream = new WriteStream();

    if(!($this->value instanceof Event))
        test()->fail('Expected an Event');

    /** @var Event $event */
    $event = $this->value;
    $event->writeBytes($stream);

    $byteToPaddedHex = function($byte) {
        $hex = dechex($byte);

        if(strlen($hex) === 2)
            return $hex;

        return "0" . $hex;
    };

    $arrayToHex = fn($bytes) => array_values(array_map(
        fn($byte) => "0x" . $byteToPaddedHex($byte),
        $bytes
    ));

    $expected = $arrayToHex($bytes);
    $actual = $arrayToHex(unpack('C*', $stream->toBinary()));

    return expect($actual)->toBe($expected);

});
