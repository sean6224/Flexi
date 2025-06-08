<?php
declare(strict_types=1);
namespace Flexi\Dynamic\Contracts;

use Closure;

/**
 * Interface DynamicMethodRegistryInterface
 *
 * Defines a contract for managing dynamic method registration and invocation.
 * Allows runtime registration, removal, and execution of callable methods,
 * along with associated metadata handling.
 *
 * @package Flexi\Dynamic\Contracts
 */
interface DynamicMethodRegistryInterface
{
    public function addMethod(string $name, Closure $callback, array $metadata = []): void;
    public function removeMethod(string $name): void;
    public function callMethod(string $name, array $arguments): mixed;
    public function getMethods(): array;
    public function hasMethod(string $name): bool;
    public function getMethodMetadata(string $name): ?array;
}