<?php

namespace PerryRylance\Midi\Attributes;

use Attribute;

#[Attribute]
class Property
{
    public function __construct(public string $alias) {}
}
