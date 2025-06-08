<?php
declare(strict_types=1);
namespace Flexi\Dynamic\Core;

use Flexi\Dynamic\Exceptions\MethodException;
use Flexi\Dynamic\Utils\TypeNormalizer;

/**
 * Responsible for invoking dynamically registered methods with validation.
 *
 * This class uses a MethodValidator to ensure arguments comply with the method's
 * definition before calling the method's callback. It also validates the return
 * type after invocation.
 */
readonly class MethodInvoker
{
    public function __construct(
        private MethodValidator $validator
    ) { }

    /**
     * @throws MethodException
     */
    public function invoke(MethodDefinition $definition, array $arguments)
    {
        $this->validator->validateMethodCall($definition, $arguments);
        $result = call_user_func_array($definition->getCallback(), $arguments);

        if ($definition->getReturnType() !== null && $definition->getReturnType() !== 'mixed')
        {
            $this->validateReturnType($result, $definition->getReturnType());
        }
        return $result;
    }

    /**
     * @throws MethodException
     */
    private function validateReturnType($result, string $expectedType): void
    {
        $expectedType = TypeNormalizer::normalizeType($expectedType);

        $actualType = gettype($result);
        
        if ($actualType === 'object')
        {
            if (!($result instanceof $expectedType))
            {
                throw MethodException::invalidMethodSignature(
                    "Return value must be instance of $expectedType"
                );
            }
        }
        elseif ($actualType !== $expectedType)
        {
            throw MethodException::invalidMethodSignature(
                "Return value must be of type $expectedType"
            );
        }
    }
}