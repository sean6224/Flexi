<?php
declare(strict_types=1);
namespace Flexi\Dynamic\Utils;

/**
 * Class TypeNormalizer
 *
 * Utility class for normalizing PHP type names to their internal representations.
 * Useful when comparing expected types with actual types returned by functions like `gettype()`.
 *
 * @package Flexi\Dynamic\Utils
 */
class TypeNormalizer
{
    private static array $typeMapping = [
        'int' => 'integer',
        'bool' => 'boolean',
        'float' => 'double',
    ];

    public static function normalizeType(string $expectedType): string
    {
        return self::$typeMapping[$expectedType] ?? $expectedType;
    }
}