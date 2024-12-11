<?php
namespace Flexi\Attributes\Hook;

use Attribute;

#[Attribute]
class Hook
{

    public function __construct(
        public array $dependencies = []
    ) {
    }
}