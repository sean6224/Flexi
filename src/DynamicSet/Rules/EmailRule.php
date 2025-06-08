<?php
declare(strict_types=1);
namespace Flexi\DynamicSet\Rules;

use Flexi\DynamicSet\Contracts\RuleInterface;

/**
 * Validation rule that checks if a value is a valid email address.
 *
 * This rule:
 * - Uses PHP's filter_var function with FILTER_VALIDATE_EMAIL to validate the format
 * - Supports a custom error message if validation fails
 *
 * @package Flexi\DynamicSet\Rules
 */
class EmailRule implements RuleInterface
{
    public function __construct(
        protected string $message = 'Invalid email address'
    ) {}

    public function validate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getErrorMessage(): string
    {
        return $this->message;
    }
}