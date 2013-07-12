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
        unset($this->collection[$offset]);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->collection);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \InvalidArgumentException('Identifier "foo" is not defined.');
        }

        if (is_object($this->collection[$offset]) && method_exists($this->collection[$offset], '__invoke')) {
            return $this->collection[$offset]($this);
        }

        return $this->collection[$offset];
    }

    public function __construct(array $values = array())
    {
        $this->collection = $values;
    }

    public function share(Closure $value)
    {

    }

    public function protect($argument1)
    {
        // TODO: write logic here
    }

    public function raw($argument1)
    {
        // TODO: write logic here
    }
}