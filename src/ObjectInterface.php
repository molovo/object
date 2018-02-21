<?php

namespace Molovo\Object;

interface ObjectInterface
{
    /**
     * Create a new object.
     *
     * @param array $values
     */
    public function __construct(array $values = []);

    /**
     * Get an object value.
     *
     * @param string $key
     */
    public function __get(string $key);

    /**
     * Set an object value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function __set(string $key, $value = null): self;

    /**
     * Return the object as an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Get a pointer to a value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function &getPointer(string $key);

    /**
     * Get a value for a nested path.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function valueForPath(string $path);

    /**
     * Set a value for a nested path.
     *
     * @param string $path
     * @param mixed  $value
     *
     * @return self
     */
    public function setValueForPath(string $path, $value = null): self;
}
