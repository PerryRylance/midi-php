<?php

namespace PerryRylance\Midi\Attributes;

use Attribute;
use LogicException;

#[Attribute]
class Setter
{
    public readonly Type $type;

    public function __construct(string | Type $type = Type::MIXED)
    {
        if(is_string($type))
        {
            if(!($this->type = Type::tryFrom($type)))
                throw new LogicException();
        }
        else
            $this->type = $type;
    }
}
