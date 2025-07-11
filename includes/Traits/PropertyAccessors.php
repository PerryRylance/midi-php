<?php

namespace PerryRylance\Midi\Traits;

use LogicException;
use OutOfRangeException;
use PerryRylance\Midi\Attributes\Getter;
use PerryRylance\Midi\Attributes\Property;
use PerryRylance\Midi\Attributes\Setter;
use PerryRylance\Midi\Attributes\Type;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

trait PropertyAccessors
{
    use AssertsIntegerTypes;

    private function getPropertyAttributes($property, ?string $attribute = null)
    {
        $reflection = new ReflectionClass($this);
        $property = $reflection->getProperty($property);
        $attributes = $property->getAttributes($attribute);

        return $attributes;
    }

    private function doesPropertyHaveAttribute($property, string $attribute): bool
    {
        $attributes = $this->getPropertyAttributes($property, $attribute);

        return !empty($attributes);
    }

    private function hasMethod($method): bool
    {
        return (new ReflectionClass($this))->hasMethod($method); 
    }

    private function isPropertyGettable($property): bool
    {
        return $this->doesPropertyHaveAttribute($property, Getter::class);
    }

    private function hasGetterMethod($property): bool
    {
        return $this->hasMethod("get$property");
    }

    private function isPropertySettable($property): bool
    {
        return $this->doesPropertyHaveAttribute($property, Setter::class);
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

    private function getAliasedSetterType(ReflectionMethod $method): ?Type
    {
        $attributes = $method->getAttributes(Property::class);

        if(empty($attributes))
            return null;

        $instance = $attributes[0]->newInstance();

        return $instance->type;
    }

    private function getPropertyType(string $internal): ?Type
    {
        $attributes = $this->getPropertyAttributes($internal, Setter::class);

        if(empty($attributes))
            throw new LogicException();

        $attribute = $attributes[0];

        return $attribute->newInstance()->type;
    }

    private function assertValueInTypeRange($value, ?Type $type): void
    {
        if(!$type || $type === Type::MIXED)
            return;

        switch($type)
        {
            case Type::BYTE:
                $this->assertByte($value);
                break;
            
            case Type::SHORT:
                $this->assertShort($value);
                break;
            
            case Type::INT:
                $this->assertUint($value);
                break;
            
            default:
                throw new LogicException();
        }
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
            if($type = $this->getAliasedSetterType($method))
                $this->assertValueInTypeRange($value, $type);

            $this->{$method->name}($value);
            return;
        }

        // NB: Plain accessors
        $internal = "_$property";

        if(!$this->isPropertySettable($internal))
            throw new LogicException("Property '$property' is not settable");

        $this->assertValueInTypeRange($value, $this->getPropertyType($internal));

        if($this->hasSetterMethod($property))
            $this->{"set$property"}($value);
        else
            $this->$internal = $value;
    }
}
