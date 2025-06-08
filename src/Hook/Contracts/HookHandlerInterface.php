<?php
namespace Flexi\Hook\Contracts;

/**
 * Interface HookHandlerInterface
 *
 * Manages registration and execution of hooks before and after methods.
 *
 * Methods:
 * - addHookBefore(): Adds a hook to run before a method with a given priority.
 * - addHookAfter(): Adds a hook to run after a method with a given priority.
 * - runHooksBefore(): Executes hooks before a method, passing and returning arguments.
 * - runHooksAfter(): Executes hooks after a method, passing result and arguments.
 */
interface HookHandlerInterface
{
    public function addHookBefore(string $methodName, callable $hook, int $priority): void;
    public function addHookAfter(string $methodName, callable $hook, int $priority): void;
    public function runHooksBefore(string $methodName, ...$args): array;
    public function runHooksAfter(string $methodName, $result, ...$args);
}