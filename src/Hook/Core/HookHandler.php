<?php
namespace Flexi\Hook\Core;

use Flexi\Hook\Contracts\HookHandlerInterface;
use SplPriorityQueue;

/**
 * Class HookHandler
 *
 * Manages before and after hooks for methods using priority queues.
 * Allows adding hooks with priorities and running them in order.
 */
class HookHandler implements HookHandlerInterface
{
    private array $hooksBefore = [];
    private array $hooksAfter = [];

    public function addHookBefore(string $methodName, callable $hook, int $priority = 0): void
    {
        $this->addHook($this->hooksBefore, $methodName, $hook, $priority);
    }

    public function addHookAfter(string $methodName, callable $hook, int $priority = 0): void
    {
        $this->addHook($this->hooksAfter, $methodName, $hook, $priority);
    }

    private function addHook(array &$hooks, string $methodName, callable $hook, int $priority): void
    {
        $queue = $hooks[$methodName] ??= new SplPriorityQueue();
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $queue->insert($hook, $priority);
    }

    public function runHooksBefore(string $methodName, ...$args): array
    {
        if (isset($this->hooksBefore[$methodName]))
        {
            $queue = clone $this->hooksBefore[$methodName];
            while (!$queue->isEmpty())
            {
                $hookItem = $queue->extract();
                $args = [$hookItem['data'](...$args)];
            }
        }
        return $args;
    }

    public function runHooksAfter(string $methodName, $result, ...$args)
    {
        if (isset($this->hooksAfter[$methodName]))
        {
            $queue = clone $this->hooksAfter[$methodName];
            while (!$queue->isEmpty())
            {
                $hookItem = $queue->extract();
                $result = $hookItem['data']($result, ...$args);
            }
        }
        return $result;
    }

    public function hasHookBefore(string $methodName): bool
    {
        return $this->hasHook($this->hooksBefore, $methodName);
    }

    public function hasHookAfter(string $methodName): bool
    {
        return $this->hasHook($this->hooksAfter, $methodName);
    }

    private function hasHook(array $hooks, string $methodName): bool
    {
        return isset($hooks[$methodName]) && !$hooks[$methodName]->isEmpty();
    }
}