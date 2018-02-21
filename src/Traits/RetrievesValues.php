<?php

namespace Molovo\Object\Traits;

trait RetrievesValues
{
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
}
