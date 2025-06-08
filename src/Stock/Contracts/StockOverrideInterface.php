<?php
namespace Flexi\Stock\Contracts;

use Closure;

/**
 * Interface for managing method overrides and saving changes.
 *
 * This interface defines methods for calling methods, overriding 
 * method implementations, and saving overrides.
 */
interface StockOverrideInterface
{
    public function call(string $name, ...$arguments): mixed;

    public function overrideMethod(string $methodName, Closure $newMethodBody, bool $saveToFile = false): void;

    public function saveOverrides(): void;
}