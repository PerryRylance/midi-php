<?php

namespace Tests\Unit;

use LogicException;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;

/**
 * @property-read int $value
 */
class ReadOnlyAccessor
{
    use PropertyAccessors;

    protected $_inaccessible = 321;

    #[Getter]
    protected $_value = 123;

    #[Getter]
    protected $_transforms = 111;

    protected function getTransforms(): int
    {
        return $this->_transforms * 2;
    }
}

/**
 * @property int $value
 */
class ReadWriteAccessor
{
    use PropertyAccessors;

    #[Getter]
    #[Setter]
    protected $_value = 123;

    #[Getter]
    #[Setter]
    protected $_transforms = 123;

    protected function setTransforms(int $value)
    {
        $this->_transforms = $value * 3;
    }
}

it('reads correct value', function() {

    $instance = new ReadOnlyAccessor;

    expect($instance->value)->toBe(123);

});

it('reads transformed value', function() {

    $instance = new ReadOnlyAccessor;

    expect($instance->transforms)->toBe(222);

});

it('throws trying to read inaccessible property', function() {

    $instance = new ReadOnlyAccessor;

    $instance->inaccessible;

})
    ->throws(LogicException::class);

it('sets correct value', function() {

    $instance = new ReadWriteAccessor;

    $instance->value = 4321;

    expect($instance->value)->toBe(4321);

});

it('sets transformed value', function() {

    $instance = new ReadWriteAccessor;

    $instance->transforms = 222;

    expect($instance->transforms)->toBe(666);

});
