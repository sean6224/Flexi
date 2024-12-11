<?php
namespace Flexi\Attributes\Hook;

use Attribute;

#[Attribute]
class HookBefore
{
    public function __construct(
        public int $priority = 0
    ) {
    }
}