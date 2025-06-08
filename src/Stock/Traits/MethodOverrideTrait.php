<?php
declare(strict_types=1);
namespace Flexi\Stock\Traits;

use Closure;
use Flexi\Stock\Contracts\OverridePersistInterface;
use Flexi\Stock\Core\OverridePersist;
use Flexi\Stock\Utils\ClosureUtils;
use ReflectionClass;
use ReflectionException;

/**
 * Trait MethodOverrideTrait
 *
 * Provides dynamic method overriding capabilities at runtime using closures.
 *
 * This trait allows replacing existing method implementations of a class by injecting new logic via closures.
 * The overrides can optionally be persisted to the source file for reuse on later runs.
 *
 * It supports:
 * - Initialization of an override mechanism using reflection on the class.
 * - Dynamic replacement of method bodies using {@see overrideMethod()}.
 * - Optional saving of overridden methods to file with {@see OverridePersistInterface}.
 * - Saving all current overrides with {@see saveOverrides()}.
 *
 * @method void getMethodRegistry() Assumed to be implemented elsewhere; required for registry access.
 * @throws ReflectionException If the class reflection fails during override operations.
 */
trait MethodOverrideTrait
{
    private OverridePersistInterface $persist;
    private ReflectionClass $reflection;

    protected function initMethodOverride(): void
    {
        $this->persist = new OverridePersist();
        $this->reflection = new ReflectionClass($this);
    }

    /**
     * @throws ReflectionException
     */
    public function overrideMethod(string $methodName, Closure $newMethodBody, bool $saveToFile = false): void
    {
        $paramDetails = ClosureUtils::getParameters($newMethodBody);
        $newMethodBodyString = ClosureUtils::extractBody($newMethodBody);

        $override = [
            'body' => $newMethodBodyString,
            'parameters' => $paramDetails,
        ];

        $this->getMethodRegistry()->override($methodName, $override);

        if ($saveToFile) {
            $this->persist->saveToFile(
                $this->reflection->getFileName(),
                [$methodName => $override]
            );
        }
    }

    public function saveOverrides(): void
    {
        $this->persist->saveToFile(
            $this->reflection->getFileName(),
            $this->getMethodRegistry()->getOverride()
        );
    }
}