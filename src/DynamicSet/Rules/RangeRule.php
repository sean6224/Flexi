<?php
namespace Flexi\DynamicSet\Rules;

use Flexi\DynamicSet\Contracts\RuleInterface;

/**
 * Validation rule that checks if a numeric value falls within a specified inclusive range.
 *
 * This rule:
 * - Validates that the input is numeric
 * - Ensures the value is greater than or equal to the minimum allowed
 * - Ensures the value is less than or equal to the maximum allowed
 * - Supports a custom error message overriding
 *
 * @package Flexi\DynamicSet\Rules
 */
class RangeRule implements RuleInterface
{
    public function __construct(
        protected float|int $min,
        protected float|int $max,
        protected string $message = ''
    ) {
        if ($this->message === '') {
            $this->message = "Value must be between $min and $max";
        }
    }

    public function validate($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        return $value >= $this->min && $value <= $this->max;
    }

    public function getErrorMessage(): string
    {
        return $this->message;
    }
}