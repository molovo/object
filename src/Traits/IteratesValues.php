<?php

namespace Molovo\Object\Traits;

use ArrayIterator;

trait IteratesValues
{
    /**
     * Implements method for IteratorAggregate to allow foreach
     * to loop through the object's values.
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->values);
    }
}
