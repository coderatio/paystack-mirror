<?php


namespace Coderatio\PaystackMirror\Services;


class ParamsBuilder
{
    protected $properties;

    public function __get($property)
    {
        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        return null;
    }

    public function __set($propertyName, $propertyValue)
    {
        $this->properties[$propertyName] = $propertyValue;
    }

    public function __isset($propertyName)
    {
        return $this->properties[$propertyName] ? $propertyName : null;
    }

    public function get(): array
    {
        return  (array) $this->properties;
    }
}