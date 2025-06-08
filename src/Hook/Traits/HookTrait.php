<?php
declare(strict_types=1);
namespace Flexi\Hook\Traits;

use Flexi\Hook\Core\HookDependency;
use Flexi\Hook\Core\HookHandler;

/**
 * Trait HookTrait
 *
 * Combines hook handling and dependency management.
 *
 * This trait provides:
 * - Initialization of hook system components.
 * - Running "before" hooks with respect to declared dependencies.
 * - Running "after" hooks with respect to declared dependencies.
 * - Prevention of recursive hook execution.
 *
 * Usage:
 * - Initialize with initHookSystem().
 * - Use runHooksBeforeWithDependencies() and runHooksAfterWithDependencies() to run hooks and dependent hooks in order.
 */
trait HookTrait
{
    use HookableTrait;
    use HookDependencyTrait;

    private array $runningHooks = [];

    protected function initHookSystem(): void
    {
        $this->hookHandler = new HookHandler();
        $this->hookDependency = new HookDependency();
    }

    public function runHooksBeforeWithDependencies(string $hookName, ...$args): array
    {
        if (!empty($this->runningHooks["before_$hookName"]))
        {
            return $args;
        }

        $this->runningHooks["before_$hookName"] = true;

        $order = $this->hookDependency->resolveDependencies($hookName);
        $order[] = $hookName;

        foreach ($order as $name)
        {
            if ($this->hookHandler->hasHookBefore($name))
            {
                $args = $this->hookHandler->runHooksBefore($name, ...$args);
            }
            else
            {
                if ($name !== $hookName && method_exists($this, $name))
                {
                    $args = [$this->$name(...$args)];
                }
            }
        }

        $this->runningHooks["before_$hookName"] = false;

        return $args;
    }

    public function runHooksAfterWithDependencies(string $hookName, $result, ...$args)
    {
        if (!empty($this->runningHooks["after_$hookName"]))
        {
            return $result;
        }

        $this->runningHooks["after_$hookName"] = true;

        $order = $this->hookDependency->resolveDependencies($hookName);
        $order[] = $hookName;

        foreach ($order as $name)
        {
            if ($this->hookHandler->hasHookAfter($name))
            {
                $result = $this->hookHandler->runHooksAfter($name, $result, ...$args);
            }
            else
            {
                if ($name !== $hookName && method_exists($this, $name))
                {
                    $result = $this->$name($result, ...$args);
                }
            }
        }

        $this->runningHooks["after_$hookName"] = false;

        return $result;
    }
}