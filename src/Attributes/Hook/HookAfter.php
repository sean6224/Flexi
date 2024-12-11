<?php
namespace Flexi\Attributes\Hook;

use Attribute;

#[Attribute]
class HookAfter
{
    public function __construct(
        public int $priority = 0
    ) {
    }
}