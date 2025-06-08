<?php
namespace Flexi\Stock\Core;

use Flexi\Stock\Contracts\MethodRegistryInterface;
use Flexi\Stock\Exceptions\invalidOverrideFormatException;
use Flexi\Stock\Exceptions\methodNotDefinedException;
use Flexi\Stock\Exceptions\NoPublicMethodsException;
use Flexi\Stock\Exceptions\OverrideExecutionException;
use Throwable;

/**
 * Registers and manages method overrides for objects.
 *
 * This class allows registering methods from an object, calling
 * methods (either original or overridden), and managing method overrides.
 */
class MethodRegistry implements MethodRegistryInterface
{
    private array $methods = [];
    private array $overrides = [];

    /**
     * @throws NoPublicMethodsException
     */
    public function register(object $instance): void
    {
        $methods = get_class_methods($instance);
        if (empty($methods)) {
            throw new NoPublicMethodsException(get_class($instance));
        }

        foreach ($methods as $method) {
            $this->methods[$method] = [$instance, $method];
        }
    }

    /**
     * @throws OverrideExecutionException|methodNotDefinedException
     */
    public function call(string $name, ...$arguments): mixed
    {
        if (isset($this->overrides[$name])) {
            return $this->executeOverride($name, $arguments);
        }

        if (!isset($this->methods[$name])) {
            throw new methodNotDefinedException($name);
        }

        return $this->methods[$name][0]->{$this->methods[$name][1]}(...$arguments);
    }

    /**
     * @throws invalidOverrideFormatException
     */
    public function override(string $methodName, array $override): void
    {
        $this->validateOverride($methodName, $override);
        $this->overrides[$methodName] = $override;
    }

    /**
     * @throws invalidOverrideFormatException
     */
    private function validateOverride(string $methodName, array $override): void
    {
        if (empty($override['body']) || !is_array($override['parameters']))
        {
            throw new invalidOverrideFormatException(
                $methodName
            );
        }
    }

    /**
     * @throws OverrideExecutionException
     */
    private function executeOverride(string $name, array $arguments): mixed
    {
        $override = $this->overrides[$name];
        
        try
        {
            $params = array_combine(
                array_column($override['parameters'], 'name'),
                array_pad($arguments, count($override['parameters']), null)
            );
        
            extract($params, EXTR_SKIP);
            return eval($override['body']);
        } catch (Throwable $e) {
            throw new OverrideExecutionException($name, $e->getMessage());
        }
    }

    public function getOverride(): array
    {
        return $this->overrides;
    }
}