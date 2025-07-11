<?php

use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Attributes\Type;
use PerryRylance\Midi\Traits\PropertyAccessors;

/**
 * @property int $byte
 * @property int $short
 * @property int $int
 */
class SizeTest
{
    use PropertyAccessors;

    protected int $_internal;

    #[Setter(Type::BYTE)]
    protected int $_byte;

    #[Setter(Type::SHORT)]
    protected int $_short;

    #[Setter(Type::INT)]
    protected int $_int;

    #[Property('external', Type::BYTE)]
    public function setExternal(int $value): void
    {
        $this->_internal = $value;
    }
}

it('throws setting negative byte', function() {

    $instance = new SizeTest();
    $instance->byte = -1;

})
    ->throws(OutOfRangeException::class);

it('throws setting too large byte', function() {

    $instance = new SizeTest();
    $instance->byte = 0xFF1;

})
    ->throws(OutOfRangeException::class);

it('throws setting negative short', function() {

    $instance = new SizeTest();
    $instance->short = -1;

})
    ->throws(OutOfRangeException::class);

it('throws setting too large short', function() {

    $instance = new SizeTest();
    $instance->short = 0xFFFF1;

})
    ->throws(OutOfRangeException::class);

it('throws setting negative int', function() {

    $instance = new SizeTest();
    $instance->int = -1;

})
    ->throws(OutOfRangeException::class);

it('throws setting too large int', function() {

    $instance = new SizeTest();
    $instance->int = 0xFFFFFFFF1;

})
    ->throws(OutOfRangeException::class);

// TODO: Test on aliased property

it('throws setting too large on aliased property', function() {

    $instance = new SizeTest();
    $instance->external = 0xFF1;

})
    ->throws(OutOfRangeException::class);
