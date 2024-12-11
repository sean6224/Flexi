<?php
namespace Flexi\Interface;

interface HookHandlerInterface
{
    public function addHookBefore(string $methodName, callable $hook, int $priority): void;
    public function addHookAfter(string $methodName, callable $hook, int $priority): void;
    public function runHooksBefore(string $methodName, ...$args): array;
    public function runHooksAfter(string $methodName, $result, ...$args);
}