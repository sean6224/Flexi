<?php
namespace Flexi\Hook\Core;

use Flexi\Hook\Contracts\HookDependencyInterface;
use RuntimeException;

/**
 * Class HookDependency
 *
 * Manages and resolves dependencies between hooks,
 * ensuring correct execution order and detecting cyclic dependencies.
 */
class HookDependency implements HookDependencyInterface
{
    private array $dependencies = [];

    public function addHookDependency(string $hookName, array $dependencies): void
    {
        $this->dependencies[$hookName] = $dependencies;
    }

    public function resolveDependencies(string $hookName): array
    {
        if (!isset($this->dependencies[$hookName])) {
            return [];
        }

        $resolved = [];
        $unresolved = [];

        foreach ($this->dependencies[$hookName] as $dependency) {
            $this->resolveDependency($dependency, $resolved, $unresolved);
        }

        return $resolved;
    }

    private function resolveDependency(string $hook, array &$resolved, array &$unresolved): void
    {
        if (in_array($hook, $resolved)) {
            return;
        }

        if (in_array($hook, $unresolved)) {
            throw new RuntimeException("Cyclic dependency detected for hook: $hook");
        }

        $unresolved[] = $hook;

        if (isset($this->dependencies[$hook])) {
            foreach ($this->dependencies[$hook] as $dependency) {
                $this->resolveDependency($dependency, $resolved, $unresolved);
            }
        }

        $resolved[] = $hook;
        unset($unresolved[array_search($hook, $unresolved)]);
    }
}