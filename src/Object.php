<?php

namespace Molovo\Object;

use IteratorAggregate;
use Molovo\Object\Traits\ConstructsObjects;
use Molovo\Object\Traits\IteratesValues;
use Molovo\Object\Traits\RetrievesValues;

class Object implements IteratorAggregate, ObjectInterface
{
    use ConstructsObjects, RetrievesValues, IteratesValues;

    /**
     * Set an object value.
     *
     * @param string     $key   The key of the value to set
     * @param null|mixed $value
     *
     * @return self
     */
    public function __set(string $key, $value = null): ObjectInterface
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * Set a value for a nested path.
     *
     * @param string $path  The path to set
     * @param mixed  $value The value to set
     *
     * @return self
     */
    public function setValueForPath(string $path, $value = null): ObjectInterface
    {
        // Explode the path into an array we can iterate over
        $bits = explode('.', $path);

        // Get a pointer to the current object
        $pointer = &$this;

        // Loop through each section of the path
        foreach ($bits as $i => $bit) {
            // If the current pointer is a nested object, store it and
            // get a pointer another level deeper
            if ($pointer instanceof self) {
                $parent  = $pointer;
                $pointer = &$pointer->getPointer($bit);
            }

            // If this isn't the last item, and the current pointer is not a
            // nested object, then we create one so that we can go deeper
            if ($i < count($bits) - 1 && !($pointer instanceof self)) {
                $parent->{$bit} = new static();
                $pointer        = &$parent->{$bit};
            }
        }

        // Set the pointer to the new value
        $pointer = $value;

        return $this;
    }
}
