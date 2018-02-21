<?php

namespace Molovo\Object;

interface MergeableObjectInterface extends ObjectInterface
{
    /**
     * Merge another object(s)'s values over this one.
     *
     * @param ObjectInterface ...$others
     *
     * @return self
     */
    public function merge(ObjectInterface ...$others): self;
}
