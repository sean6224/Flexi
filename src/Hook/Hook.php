<?php
namespace Flexi\Hook;

use ReflectionClass;
use Flexi\Attributes\Hook\HookBefore;
use Flexi\attributes\Hook\HookAfter;

class Hook extends HookHandler
{
    private static array $reflectionCache = [];
    private static int $cacheLimit = 10;

    private array $attributeMethods = [
        HookBefore::class => 'addHookBefore',
        HookAfter::class  => 'addHookAfter',
    ];

    public function __construct()
    {
        $this->applyHooksFromAttributes($this);
    }

    public function applyHooksFromAttributes(object $object): void
    {
        $className = get_class($object);
        $reflection = $this->getReflection($object, $className);
        foreach ($reflection->getMethods() as $method)
        {
            $methodName = $method->getName();
            foreach ($method->getAttributes() as $attribute)
            {
                $hook = $attribute->newInstance();
                $hookClass = get_class($hook);
                print_r($hookClass);
                if (isset($this->attributeMethods[$hookClass])) {
                    $this->{$this->attributeMethods[$hookClass]}($methodName, [$object, $methodName], $hook->priority);
                }
            }
        }
    }

    private function getReflection(object $object, string $className): ReflectionClass
    {
        if (!isset(self::$reflectionCache[$className]))
        {
            $this->manageCache($className);
            self::$reflectionCache[$className] = new ReflectionClass($object);
        }

        return self::$reflectionCache[$className];
    }

    private function manageCache(string $className): void
    {
        if (count(self::$reflectionCache) >= self::$cacheLimit) {
            array_shift(self::$reflectionCache);
        }
    }
}
