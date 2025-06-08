<?php
namespace Flexi\Stock\Contracts;

/**
 * Interface for managing method registration and overrides.
 *
 * This interface defines methods for registering methods, calling them,
 * overriding method implementations, and retrieving overrides.
 */
interface MethodRegistryInterface
{
    public function register(object $instance): void;

    public function call(string $name, ...$arguments): mixed;

    public function override(string $methodName, array $override): void;

    public function getOverride(): array;
}