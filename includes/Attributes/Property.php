<?php

namespace PerryRylance\Midi\Attributes;

use Attribute;

#[Attribute]
class Property
{
	public function __construct(public readonly string $alias, public readonly Type $type = Type::MIXED)
	{
	}
}
