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
        throw new \Exception('Iam invoked with '.$offset);
    }

    public function offsetGet($offset)
    {
        return $this->trueOffsetGet($offset);
    }

    public function trueOffsetGet($offset)
    {
        if (is_object($this->collection[$offset]) && method_exists($this->collection[$offset], '__invoke')) {
            return $this->executeFactory($offset);
        }

        return $this->collection[$offset];
    }

    public function executeFactory($offset)
    {
        return $this->collection[$offset]($this);
    }
}