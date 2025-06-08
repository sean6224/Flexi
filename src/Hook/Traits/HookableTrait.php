<?php
declare(strict_types=1);
namespace Flexi\Hook\Traits;

use Flexi\Hook\Attributes\HookAfter;
use Flexi\Hook\Attributes\HookBefore;
use Flexi\Hook\Core\HookHandler;
use ReflectionClass;

/**
 * Trait HookableTrait
 *
 * Provides an interface to register and run hooks (before and after) on methods.
 *
 * This trait delegates hook management to an internal HookHandler instance.
 *
 * Usage:
 * - Initialize with initHookHandler().
 * - Use addHookBefore/addHookAfter to register hooks with optional priority.
 * - Use runHooksBefore/runHooksAfter to execute registered hooks.
 * - Use hasHookBefore/hasHookAfter to check hook existence.
 */
trait HookableTrait
{
    private HookHandler $hookHandler;

    protected function initHookHandler(): void
    {
        $this->hookHandler = new HookHandler();
    }

    public function addHookBefore(string $methodName, callable $hook, int $priority = 0): void
    {
        $this->hookHandler->addHookBefore($methodName, $hook, $priority);
    }

    public function addHookAfter(string $methodName, callable $hook, int $priority = 0): void
    {
        $this->hookHandler->addHookAfter($methodName, $hook, $priority);
    }

    public function runHooksBefore(string $methodName, ...$args): array
    {
        return $this->hookHandler->runHooksBefore($methodName, ...$args);
    }

    public function runHooksAfter(string $methodName, $result, ...$args)
    {
        return $this->hookHandler->runHooksAfter($methodName, $result, ...$args);
    }

    public function hasHookBefore(string $methodName): bool
    {
        return $this->hookHandler->hasHookBefore($methodName);
    }

    public function hasHookAfter(string $methodName): bool
    {
        return $this->hookHandler->hasHookAfter($methodName);
    }

    protected function applyHooksFromAttributes(object $obj): void
    {
        $refClass = new ReflectionClass($obj);
        foreach ($refClass->getMethods() as $method)
        {
            $beforeAttrs = $method->getAttributes(HookBefore::class);
            foreach ($beforeAttrs as $attr)
            {
                $before = $attr->newInstance();
                $this->addHookBefore('save', [$obj, $method->getName()], $before->priority);
            }

            $afterAttrs = $method->getAttributes(HookAfter::class);
            foreach ($afterAttrs as $attr)
            {
                $after = $attr->newInstance();
                $this->addHookAfter('save', [$obj, $method->getName()], $after->priority);
            }
        }
    }
}