<?php
namespace Flexi\Hook\Contracts;

/**
 * Interface HookDependencyInterface
 *
 * Manages hook dependencies and resolves their execution order.
 *
 * Methods:
 * - addHookDependency(): Registers dependencies for a given hook.
 * - resolveDependencies(): Returns an ordered list of dependencies for a given hook.
 */
interface HookDependencyInterface
{
    public function addHookDependency(string $hookName, array $dependencies): void;
    public function resolveDependencies(string $hookName): array;
}