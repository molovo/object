<?php

namespace Molovo\Object;

use ArrayIterator;
use IteratorAggregate;

class Object implements IteratorAggregate, ObjectInterface
{
    /**
     * The stored object values.
     *
     * @var array
     */
    private $values = [];

    /**
     * Create a new object.
     *
     * @param array $values The object values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as &$value) {
            // Value is an array
            if (is_array($value) && !empty($value)) {
                // Copy the array to avoid modifying the original
                $array = $value;

                // Sort the array, and get the keys
                ksort($array);
                $keys = array_keys($array);

                // If the keys do not match a range of it's size, then the array
                // is non-associative, so we create a new object to nest within
                // our object's values array
                if ($keys !== range(0, count($array) - 1)) {
                    $value = new static($value);
                    continue;
                }
            }
        }

        $this->values = $values;
    }

    /**
     * Get an object value.
     *
     * @param string $key The key of the value to get
     *
     * @return mixed The value
     */
    public function __get(string $key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
    }

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
     * Return the object as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $rtn = [];

        foreach ($this->values as $key => $value) {
            if ($value instanceof static) {
                $value = $value->toArray();
            }
            $rtn[$key] = $value;
        }

        return $rtn;
    }

    /**
     * Get a pointer to a value.
     *
     * @param string $key The key to get the pointer for
     *
     * @return mixed
     */
    public function &getPointer(string $key)
    {
        if (!isset($this->values[$key])) {
            $this->values[$key] = null;
        }

        return $this->values[$key];
    }

    /**
     * Get a value for a nested path.
     *
     * @param string $path The path to fetch
     *
     * @return mixed The value
     */
    public function valueForPath(string $path)
    {
        $bits = explode('.', $path);

        $value = $this->getPointer(array_shift($bits));
        foreach ($bits as $bit) {
            if ($value instanceof static) {
                $value = $value->{$bit};
                continue;
            }

            return;
        }

        return $value;
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
