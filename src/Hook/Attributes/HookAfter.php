<?php
namespace Flexi\Hook\Attributes;

use Attribute;

#[Attribute]
class HookAfter
{
    public function __construct(
        public int $priority = 0
    ) {
    }
}