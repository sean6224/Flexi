<?php
namespace Flexi\Hook\Attributes;

use Attribute;

#[Attribute]
class Hook
{
    public function __construct(
        public array $dependencies = []
    ) {
    }
}