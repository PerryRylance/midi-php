<?php

namespace PerryRylance\Midi\Collections;

use Aeviiq\Collection\ObjectCollection;

class Collection extends ObjectCollection
{
    public function each(callable $callback): void
    {
        foreach($this->getIterator() as $value)
            $callback($value);
    }
}
