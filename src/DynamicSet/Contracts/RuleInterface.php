<?php
namespace Flexi\DynamicSet\Contracts;

/**
 * Interface RuleInterface
 *
 * Defines a contract for validation rules.
 *
 * Implementing classes should provide logic to validate a value
 * and supply an error message when validation fails.
 */
interface RuleInterface
{
    public function validate($value): bool;
    public function getErrorMessage(): string;
}