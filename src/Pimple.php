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
        return array_key_exists($offset, $this->collection);
    }

    public function offsetGet($offset)
    {
        if (is_object($this->collection[$offset]) && method_exists($this->collection[$offset], '__invoke')) {
            return $this->collection[$offset]($this);
        }

        return $this->collection[$offset];
    }
}