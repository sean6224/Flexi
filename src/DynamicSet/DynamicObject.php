<?php
declare(strict_types=1);
namespace Flexi\DynamicSet;

use Flexi\DynamicSet\Traits\FluentSetterTrait;
use Flexi\DynamicSet\Traits\SerializableTrait;
use Flexi\DynamicSet\Traits\ValidatableTrait;

/**
* Class DynamicObject
 *
 * Base abstract class providing:
 * - Fluent setters for dynamic properties (FluentSetterTrait)
* - Serialization and deserialization to/from arrays (SerializableTrait)
* - Validation of properties based on defined rules (ValidatableTrait)
*
 * Allows defining hooks to extend behavior during property setting,
 * serialization, deserialization, and validation processes.
 *
 * @package Flexi\DynamicSet
*/
abstract class DynamicObject
{
    use FluentSetterTrait;
    use SerializableTrait;
    use ValidatableTrait;

    public static function new(): static
    {
        return new static();
    }

    protected function beforeSet(string $property, $value) { return $value; }
    protected function afterSet(string $property, $value): void {}
    protected function beforeSerialize(string $property, $value) { return $value; }
    protected function afterSerialize(string $property, $value) { return $value; }
    protected function beforeDeserialize(string $property, $value) { return $value; }
    protected function afterDeserialize(string $property, $value) { return $value; }
    protected function beforeValidate(): void {}
    protected function afterValidate(): void {}

    abstract protected function rules(): array;
}