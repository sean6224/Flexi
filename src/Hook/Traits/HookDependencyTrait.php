<?php
declare(strict_types=1);
namespace Flexi\Hook\Traits;

use Flexi\Hook\Core\HookDependency;

/**
 * Trait HookDependencyTrait
 *
 * Provides management of hook dependencies within a class.
 *
 * This trait offers methods to:
 * - Initialize the hook dependency manager.
 * - Add dependencies for specific hooks.
 * - Resolve and retrieve dependencies for a given hook.
 */
trait HookDependencyTrait
{
    private HookDependency $hookDependency;

    protected function initHookDependency(): void
    {
        $this->hookDependency = new HookDependency();
    }

    public function addHookDependency(string $hookName, array $dependencies): void
    {
        $this->hookDependency->addHookDependency($hookName, $dependencies);
    }

    public function resolveHookDependencies(string $hookName): array
    {
        return $this->hookDependency->resolveDependencies($hookName);
    }
}