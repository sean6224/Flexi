<?php
namespace Flexi\Stock\Core;

use Attribute;

/**
 * Attribute to mark a class or property as part of a stock configuration.
 *
 * This attribute can be used to annotate classes or properties to provide metadata
 * related to stock functionality such as names and additional arguments.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Stock
{
    public function __construct(public string $name, public array $args = []) {}
}