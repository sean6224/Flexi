<?php
namespace Flexi\Stock\Contracts;

/**
 * Interface for persisting method overrides to a file.
 *
 * This interface defines a method for saving overrides to a file.
 */
interface OverridePersistInterface
{
    public function saveToFile(string $filePath, array $overrides): void;
}