<?php
namespace Flexi\Attributes;

#[\Attribute]
class Stock
{
    public function __construct(public string $name, public array $args = []) {}
}