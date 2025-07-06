<?php

namespace PerryRylance\Midi\Traits;

use LogicException;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Setter;
use ReflectionClass;

trait PropertyAccessors
{
    private function isPropertyAccessable($property, string $attribute): bool
    {
        $reflection = new ReflectionClass($this);
        $property = $reflection->getProperty($property);
        $attributes = $property->getAttributes($attribute);

        return !empty($attributes);
    }

    private function hasMethod($method): bool
    {
        return (new ReflectionClass($this))->hasMethod($method); 
    }

    private function isPropertyGettable($property): bool
    {
        return $this->isPropertyAccessable($property, Getter::class);
    }

    private function hasGetterMethod($property): bool
    {
        return $this->hasMethod("get$property");
    }

    private function isPropertySettable($property): bool
    {
        return $this->isPropertyAccessable($property, Setter::class);
    }

    private function hasSetterMethod($property): bool
    {
        return $this->hasMethod("set$property");
    }

    public function __get($property)
    {
        $internal = "_$property";

        if(!$this->isPropertyGettable($internal))
            throw new LogicException("Property '$property' is not gettable");

        if($this->hasGetterMethod($property))
            return $this->{"get$property"}();

        return $this->$internal;
    }

    public function __set($property, $value)
    {
        $internal = "_$property";

        if(!$this->isPropertySettable($internal))
            throw new LogicException("Property '$property' is not settable");

        if($this->hasSetterMethod($property))
            $this->{"set$property"}($value);
        else
            $this->$internal = $value;
    }
}
