<?php

namespace Molovo\Object\Traits;

use Molovo\Object\MergeableObjectInterface;
use Molovo\Object\ObjectInterface;

trait MergesObjects
{
    /**
     * Merge another object(s)'s values over this one.
     *
     * @param ObjectInterface ...$others
     *
     * @return self
     */
    public function merge(ObjectInterface ...$others): MergeableObjectInterface
    {
        $new = clone $this;

        foreach ($others as $object) {
            foreach ($object as $key => $value) {
                if ($new->{$key} instanceof MergeableObjectInterface && $value instanceof ObjectInterface) {
                    $new->{$key} = $new->{$key}->merge($value);
                    continue;
                }

                $new->{$key} = $value;
            }
        }

        return $new;
    }
}
