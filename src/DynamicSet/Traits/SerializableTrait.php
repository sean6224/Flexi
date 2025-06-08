<?php
declare(strict_types=1);
namespace Flexi\DynamicSet\Traits;

/**
 * Trait SerializableTrait
 *
 * Provides methods to serialize an object to an array and to instantiate
 * an object from an array representation.
 *
 * Supports optional lifecycle hooks to customize serialization and deserialization:
 * - beforeSerialize(string $key, mixed $value): mixed
 * - afterSerialize(string $key, mixed $value): mixed
 * - beforeDeserialize(string $key, mixed $value): mixed
 * - afterDeserialize(string $key, mixed $value): mixed
 *
 * Usage:
 * - toArray() converts public and protected properties recursively to arrays.
 * - fromArray(array $data) creates a new instance and populates properties from the array.
 */
trait SerializableTrait
{
    public function toArray(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $value)
        {
            if (method_exists($this, 'beforeSerialize'))
            {
                $value = $this->beforeSerialize($key, $value);
            }

            if ($value instanceof self)
            {
                $result[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $result[$key] = array_map(fn($v) => $v instanceof self ? $v->toArray() : $v, $value);
            } else {
                $result[$key] = $value;
            }

            if (method_exists($this, 'afterSerialize')) {
                $result[$key] = $this->afterSerialize($key, $result[$key]);
            }
        }

        return $result;
    }

    public static function fromArray(array $data): static
    {
        $instance = new static();

        foreach ($data as $key => $value)
        {
            if (!property_exists($instance, $key))
            {
                continue;
            }

            if (method_exists($instance, 'beforeDeserialize'))
            {
                $value = $instance->beforeDeserialize($key, $value);
            }

            $currentValue = $instance->$key;

            if (is_object($currentValue) && method_exists($currentValue, 'fromArray'))
            {
                $instance->$key = $currentValue::fromArray($value);
            } else {
                $instance->$key = $value;
            }

            if (method_exists($instance, 'afterDeserialize'))
            {
                $instance->$key = $instance->afterDeserialize($key, $instance->$key);
            }
        }

        return $instance;
    }
}