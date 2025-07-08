<?php

namespace Tests\Unit;

use LogicException;
use PerryRylance\Midi\Traits\PropertyAccessors;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Attributes\Setter;

/**
 * @property-read int $value
 */
class ReadOnlyAccessor
{
    use PropertyAccessors;

    private $_inaccessible = 321;

    #[Getter]
    private $_value = 123;

    #[Getter]
    private $_transforms = 111;

    private function getTransforms(): int
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
    private $_value = 123;

    #[Getter]
    #[Setter]
    private $_transforms = 123;

    private function setTransforms(int $value)
    {
        $this->_transforms = $value * 3;
    }
}

/**
 * @property int $external
 */
class AliasedAccessor
{
    use PropertyAccessors;

    private $_internal = 54321;

    public function getRawInternal(): int
    {
        return $this->_internal;
    }

    #[Property("external")]
    protected function getInternal()
    {
        return $this->_internal - 50000;
    }

    #[Property("external")]
    protected function setInternal(int $value)
    {
        $this->_internal = $value + 999;
    }

    #[Property("ambiguous")]
    protected function getFirstAmbiguous()
    {
        return 0;
    }

    #[Property("ambiguous")]
    protected function getSecondAmbiguous()
    {
        return 0;
    }

    #[Property("ambiguous")]
    protected function setFirstAmbiguous() {}

    #[Property("ambiguous")]
    protected function setSecondAmbiguous() {}
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

it('reads aliased value', function() {

    $instance = new AliasedAccessor;

    expect($instance->external)->toBe(4321);

});

it('throws on reading ambiguous property alias', function() {

    $instance = new AliasedAccessor;
    $instance->ambiguous;

})
    ->throws(LogicException::class);

it('writes aliased value', function() {

    $instance = new AliasedAccessor;
    $instance->external = 1;

    expect($instance->getRawInternal())->toBe(1000);

});

it('throws on writing ambiguous property alias', function() {

    $instance = new AliasedAccessor;
    $instance->ambiguous = "test";

})
    ->throws(LogicException::class);
