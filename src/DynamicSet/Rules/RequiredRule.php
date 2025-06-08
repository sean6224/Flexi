<?php
declare(strict_types=1);
namespace Flexi\DynamicSet\Rules;

use Flexi\DynamicSet\Contracts\RuleInterface;

/**
 * Validation rule that checks if a value is present and not empty.
 *
 * This rule:
 * - Validates that the value is not empty (using PHP's `empty()` function)
 * - Supports a custom error message if validation fails
 *
 * @package Flexi\DynamicSet\Rules
 */
class RequiredRule implements RuleInterface
{
    public function __construct(
        protected string $message = 'Value is required'
    ) {}

    public function validate($value): bool
    {
        return !empty($value);
    }

    public function getErrorMessage(): string
    {
        return $this->message;
    }
}