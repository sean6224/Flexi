<?php
declare(strict_types=1);
namespace Flexi\DynamicSet\Rules;

use Flexi\DynamicSet\Contracts\RuleInterface;

/**
 * Validation rule that checks if a string value has a minimum length.
 *
 * This rule:
 * - Validates that the value is a string
 * - Ensures the string length is at least the specified minimum
 * - Supports a custom error message if validation fails
 *
 * @package Flexi\DynamicSet\Rules
 */
class MinLengthRule implements RuleInterface
{
    public function __construct(
        protected int $minLength,
        protected string $message = ''
    ) {
        if ($this->message === '')
        {
            $this->message = "Value must have at least {$minLength} characters";
        }
    }

    public function validate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        return mb_strlen($value) >= $this->minLength;
    }

    public function getErrorMessage(): string
    {
        return $this->message;
    }
}