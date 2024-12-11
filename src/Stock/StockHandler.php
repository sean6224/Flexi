<?php
namespace Flexi\Stock;

use ReflectionClass;
use Flexi\Attributes\Stock;
use ReflectionMethod;
use Exception;

class StockHandler
{
    private array $stocks = [];
    private array $overrides = [];

    public function __construct()
    {
        $this->register($this);
    }

    private function register(object $instance): void
    {
        $reflection = new ReflectionClass($instance);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            $attributes = $method->getAttributes(Stock::class);
            foreach ($attributes as $attribute)
            {
                /** @var Stock $stock */
                $stock = $attribute->newInstance();
                $this->stocks[$stock->name] = [
                    'method' => [$instance, $method->getName()],
                    'args' => $stock->args,
                ];
            }
        }
    }

    /**
     * @throws Exception
     */
    public function call(string $name, ...$arguments)
    {
        if (isset($this->stocks[$name]))
        {
            $stock = $this->stocks[$name];
            if (isset($this->overrides[$name]))
            {
                return call_user_func_array($this->overrides[$name], $arguments);
            }
            return call_user_func_array($stock['method'], $arguments);
        }

        throw new Exception("Stock $name not defined");
    }

    public function overrideMethod(string $methodName, callable $newMethod): void
    {
        if (!isset($this->stocks[$methodName]) && !isset($this->overrides[$methodName])) {
            throw new Exception("Method $methodName not found for overriding.");
        }
    
        $this->overrides[$methodName] = $newMethod;
    }
}
