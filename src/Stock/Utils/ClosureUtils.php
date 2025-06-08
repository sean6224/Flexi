<?php
namespace Flexi\Stock\Utils;

use Closure;
use ReflectionException;
use ReflectionFunction;

/**
 * Class ClosureUtils
 *
 * Provides utility functions for inspecting and extracting information from closures.
 *
 * This helper class simplifies access to closure metadata such as parameter definitions
 * and the source body of the closure itself. It is used primarily for serialization or
 * dynamic method override mechanisms.
 *
 * Features:
 * - Retrieve parameter names and types from a closure.
 * - Extract the raw source code body of a closure from its defining file.
 *
 * @throws ReflectionException If the closure cannot be reflected (e.g., internal or invalid).
 */
class ClosureUtils
{
    /**
     * @throws ReflectionException
     */
    public static function getParameters(Closure $closure): array
    {
        $reflection = new ReflectionFunction($closure);
        $parameters = $reflection->getParameters();

        $paramDetails = [];
        foreach ($parameters as $parameter)
        {
            $paramDetails[] = [
                'name' => $parameter->getName(),
                'type' => $parameter->getType() ? $parameter->getType()->getName() : 'mixed',
            ];
        }

        return $paramDetails;
    }

    /**
     * @throws ReflectionException
     */
    public static function extractBody(Closure $closure): string
    {
        $reflection = new ReflectionFunction($closure);
        $file = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $code = file($file);
        $sourceCode = implode("", array_slice($code, $startLine - 1, $endLine - $startLine + 1));

        preg_match('/\{(.*)}/s', $sourceCode, $matches);
        return $matches[1] ?? '';
    }
}
