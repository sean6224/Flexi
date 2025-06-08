<?php
namespace Flexi\Hook\Attributes;

use Attribute;

#[Attribute]
class HookBefore
{
    public function __construct(
        public int $priority = 0
    ) {
    }
}