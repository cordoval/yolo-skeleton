<?php

class Pimple implements ArrayAccess
{
    protected $collection = [];

    public function offsetSet($offset, $value)
    {
        $this->collection[$offset] = $value;
    }

    public function offsetUnset($offset)
    {

    }

    public function offsetExists($offset)
    {

    }

    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }
}
