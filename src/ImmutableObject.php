<?php

namespace Molovo\Object;

use IteratorAggregate;
use Molovo\Object\Exception\ImmutabilityViolationException;
use Molovo\Object\Traits\ConstructsObjects;
use Molovo\Object\Traits\IteratesValues;
use Molovo\Object\Traits\RetrievesValues;

class ImmutableObject implements IteratorAggregate, ObjectInterface
{
    use ConstructsObjects, RetrievesValues, IteratesValues;

    /**
     * Set an object value.
     *
     * @param string     $key   The key of the value to set
     * @param null|mixed $value
     *
     * @throws ImmutabilityViolationException
     *
     * @return ObjectInterface
     */
    public function __set(string $key, $value = null): ObjectInterface
    {
        throw new ImmutabilityViolationException("Unable to set property '${key}': object is immutable.");
    }

    /**
     * Set a value for a nested path.
     *
     * @param string $path  The path to set
     * @param mixed  $value The value to set
     *
     * @throws ImmutabilityViolationException
     *
     * @return ObjectInterface
     */
    public function setValueForPath(string $path, $value = null): ObjectInterface
    {
        throw new ImmutabilityViolationException("Unable to set path '${path}': object is immutable.");
    }
}
