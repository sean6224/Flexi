<?php
declare(strict_types=1);
namespace Flexi\DynamicSet\Traits;

use BadMethodCallException;
use Flexi\DynamicSet\Exception\InvalidPropertyException;

/**
 * Trait FluentSetterTrait
 *
 * Provides dynamic fluent setters for object properties using magic __call method.
 *
 * This trait enables calling methods named in the pattern `set_propertyName($value)` to
 * dynamically set the value of the corresponding property `$propertyName`.
 *
 * It supports:
 * - Validation of property existence, throwing {@see InvalidPropertyException} if the property is not found.
 * - Optional lifecycle hooks:
 *   - `beforeSet(string $property, mixed $value)` to preprocess the value before setting.
 *   - `applyMutators(string $property, mixed $value)` to transform the value via mutators.
 *   - `afterSet(string $property, mixed $value)` to execute logic after the value is set.
 * - Fluent interface: returns `$this` to allow method chaining.
 *
 * @throws InvalidPropertyException If the targeted property does not exist in the class.
 * @throws BadMethodCallException If the called method does not follow the expected `set_*` pattern.
 */
trait FluentSetterTrait
{
    /**
     * @throws InvalidPropertyException
     */
    public function __call(string $name, array $arguments)
    {
        if (preg_match('/^set_(.+)$/', $name, $matches))
        {
            $property = $matches[1];

            if (!property_exists($this, $property)) {
                throw new InvalidPropertyException($property, static::class);
            }

            $value = $arguments[0];

            if (method_exists($this, 'beforeSet')) {
                $value = $this->beforeSet($property, $value);
            }

            if (method_exists($this, 'applyMutators')) {
                $value = $this->applyMutators($property, $value);
            }

            $this->$property = $value;

            if (method_exists($this, 'afterSet')) {
                $this->afterSet($property, $value);
            }

            return $this;
        }

        throw new BadMethodCallException("Method '$name' not found in " . static::class);
    }
}