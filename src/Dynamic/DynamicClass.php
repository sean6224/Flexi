<?php
namespace Flexi\Dynamic;

use Exception;

class DynamicClass
{
    private $methods = [];
    private $properties = [];

    public function addMethod(string $name, callable $callback) {
        $this->methods[$name] = $callback;
    }

    public function removeMethod(string $name): void
    {
        if (!isset($this->methods[$name])) {
            throw new Exception("Method $name does not exist");
        }
        unset($this->methods[$name]);
    }

    public function __call($name, $arguments) {
        if (isset($this->methods[$name])) {
            return call_user_func_array($this->methods[$name], $arguments);
        }
        throw new Exception("Method $name not defined");
    }

    public function __set(string $name, $value): void
    {
        $this->properties[$name] = $value;
    }

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        throw new Exception("Property $name not defined");
    }

    public function __isset(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function __unset(string $name): void
    {
        if (isset($this->properties[$name])) {
            unset($this->properties[$name]);
        }
    }
    
    public function getMethods(): array
    {
        return array_keys($this->methods);
    }

    public function getProperties(): array
    {
        return array_keys($this->properties);
    }
}
