<?php

namespace Molovo\Object\Traits;

trait ConstructsObjects
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
}
