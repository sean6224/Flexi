<?php
declare(strict_types=1);
namespace Flexi\DynamicSet\Traits;

use Flexi\DynamicSet\Exception\ValidationException;

/**
 * Trait ValidatableTrait
 *
 * Adds validation capabilities to a model by enforcing validation rules on its properties.
 *
 * The consuming class must implement the abstract `rules()` method that returns
 * an array mapping property names to arrays of RuleInterface instances.
 *
 * Validation:
 * - Executes optional lifecycle hooks: `beforeValidate()` and `afterValidate()`.
 * - Validates each property according to its associated rules.
 * - Throws ValidationException if any rule validation fails, with a detailed message.
 * - Recursively validates nested objects if they have a `validate()` method.
 */
trait ValidatableTrait
{
    /**
     * Override in the model class, such as
     * return ['property' => [RuleInterface, RuleInterface, ...]]
     */
    abstract protected function rules(): array;

    /**
     * @throws ValidationException
     */
    public function validate(): void
    {
        if (method_exists($this, 'beforeValidate'))
        {
            $this->beforeValidate();
        }

        foreach ($this->rules() as $property => $rules)
        {
            $value = $this->$property ?? null;

            foreach ($rules as $rule)
            {
                if (!$rule->validate($value))
                {
                    throw new ValidationException("Validation failed for $property ({$rule->getErrorMessage()})");
                }
            }
        }

        foreach (get_object_vars($this) as $value)
        {
            if (is_object($value) && method_exists($value, 'validate'))
            {
                $value->validate();
            }
        }

        if (method_exists($this, 'afterValidate'))
        {
            $this->afterValidate();
        }
    }
}