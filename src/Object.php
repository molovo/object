<?php

namespace Molovo\Object;

use ArrayIterator;
use IteratorAggregate;

class Object implements IteratorAggregate
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
            if (is_array($value)) {
                $value = new static($value);
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
    public function &__get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        $null = null
        return $null;
    }

    /**
     * Set an object value.
     *
     * @param string $key The key of the value to set
     *
     * @return mixed The value
     */
    public function &__set($key, $value = null)
    {
        return $this->values[$key] = $value;
    }

    /**
     * Return the object as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $rtn = [];

        foreach ($this->values as $key => $value) {
            if ($value instanceof self) {
                $value = $value->toArray();
            }
            $rtn[$key] = $value;
        }

        return $rtn;
    }

    /**
     * Get a value for a nested path.
     *
     * @param string $path The path to fetch
     *
     * @return mixed The value
     */
    public function valueForPath($path)
    {
        $bits = explode('.', $path);

        $value = $this->values[array_shift($bits)];
        foreach ($bits as $bit) {
            if ($value instanceof self) {
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
     * @param string $value The value to set
     *
     * @return mixed The value
     */
    public function setValueForPath($path, $value = null)
    {
        $bits = explode('.', $path);

        $pointer = &$this->values[array_shift($bits)];
        foreach ($bits as $bit) {
            if ($pointer instanceof self) {
                $pointer = &$pointer->{$bit};
                continue;
            }

            $object  = new static;
            $pointer = &$object->{$bit};
        }

        return $pointer = $value;
    }

    /**
     * Fulfils the IteratorAggregate implementation to allow foreach
     * to loop through the object's values.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }
}
