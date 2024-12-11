<?php
namespace Flexi\Hook;

use Flexi\Interface\HookDependencyInterface;
use RuntimeException;

class HookDependency implements HookDependencyInterface
{
    private array $hooks = [];

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