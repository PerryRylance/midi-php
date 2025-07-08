<?php

namespace PerryRylance\Midi\Traits;

use LogicException;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Attributes\Setter;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

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

    private function getAliasedMethod(string $getOrSet, $property): ?ReflectionMethod
    {
        if(!match($getOrSet) {
            'get' => true,
            'set' => true,
            default => false
        })
            throw new LogicException();
        
        $regex = "/^$getOrSet/";
        $reflect = new ReflectionClass($this);

        $methods = array_values(array_filter($reflect->getMethods(), function(ReflectionMethod $method) use ($regex, $property) {

            if(!preg_match($regex, $method->name))
                return false;

            $attributes = array_filter($method->getAttributes(Property::class), fn(ReflectionAttribute $attribute) => $attribute->newInstance()->alias === $property);

            return !empty($attributes);

        }));

        if(empty($methods))
            return null;

        if(count($methods) > 1)
            throw new LogicException("Ambiguous property attribute");

        return $methods[0];
    }

    public function __get($property)
    {
        // NB: Aliased accessors
        if($method = $this->getAliasedMethod('get', $property))
            return $this->{$method->name}();

        // NB: Plain accessors
        $internal = "_$property";

        if(!$this->isPropertyGettable($internal))
            throw new LogicException("Property '$property' is not gettable");

        if($this->hasGetterMethod($property))
            return $this->{"get$property"}();

        return $this->$internal;
    }

    public function __set($property, $value)
    {
        // NB: Aliased accessors
        if($method = $this->getAliasedMethod('set', $property))
        {
            $this->{$method->name}($value);
            return;
        }

        // NB: Plain accessors
        $internal = "_$property";

        if(!$this->isPropertySettable($internal))
            throw new LogicException("Property '$property' is not settable");

        if($this->hasSetterMethod($property))
            $this->{"set$property"}($value);
        else
            $this->$internal = $value;
    }
}
