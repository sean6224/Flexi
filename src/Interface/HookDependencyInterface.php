<?php
namespace Flexi\Interface;

interface HookDependencyInterface
{
    public function addHookDependency(string $hookName, array $dependencies): void;
    public function resolveDependencies(string $hookName): array;
}