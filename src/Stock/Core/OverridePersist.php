<?php
namespace Flexi\Stock\Core;

use Flexi\Stock\Contracts\OverridePersistInterface;
use Flexi\Stock\Exceptions\FailedOverwriteException;
use Flexi\Stock\Exceptions\FileAccessibleException;
use Flexi\Stock\Exceptions\FileEmptyOrNotReadException;
use PhpParser\Error as PhpParserError;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RuntimeException;
use SplFileObject;

/**
 * Handles saving overrides to PHP files by modifying their AST.
 *
 * This class allows applying method body and parameter overrides
 * to a PHP file, parsing it, applying modifications, and saving
 * the changes back to the file.
 */
class OverridePersist implements OverridePersistInterface
{
    /**
     * @throws FileAccessibleException
     * @throws FileEmptyOrNotReadException
     * @throws FailedOverwriteException
     */
    public function saveToFile(string $filePath, array $overrides): void
    {
        $this->validateFilePath($filePath);
        $fileContents = $this->readFileContents($filePath);

        $ast = $this->parseFileContents($fileContents, $filePath);
        $modifiedAst = $this->applyOverridesToAst($ast, $overrides);
        $modifiedCode = $this->convertAstToCode($modifiedAst);

        $this->writeFileContents($filePath, $modifiedCode);
    }

    /**
     * @throws FileAccessibleException
     */
    private function validateFilePath(string $filePath): void
    {
        if (!is_readable($filePath) || !is_writable($filePath)) {
            throw new FileAccessibleException($filePath);
        }
    }

    /**
     * @throws FileEmptyOrNotReadException
     */
    private function readFileContents(string $filePath): string
    {
        $file = new SplFileObject($filePath, 'r');
        $fileContents = '';

        while (!$file->eof()) {
            $fileContents .= $file->fgets();
        }

        if (trim($fileContents) === '') {
            throw new FileEmptyOrNotReadException($filePath);
        }

        return $fileContents;
    }

    private function parseFileContents(string $contents, string $filePath): array
    {
        try {
            $parserFactory = new ParserFactory();
            $parser = $parserFactory->createForHostVersion();
            return $parser->parse($contents);
        } catch (PhpParserError $e) {
            throw new RuntimeException("Syntax error in $filePath: " . $e->getMessage());
        }
    }

    private function applyOverridesToAst(array $ast, array $overrides): array
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor(new MethodOverrideVisitor($overrides));
        return $traverser->traverse($ast);
    }

    private function convertAstToCode(array $ast): string
    {
        $prettyPrinter = new Standard();
        return $prettyPrinter->prettyPrintFile($ast);
    }

    /**
     * @throws FailedOverwriteException
     */
    private function writeFileContents(string $filePath, string $content): void
    {
        $tempFile = $filePath . '.tmp';

        $file = new SplFileObject($tempFile, 'w');
        $file->fwrite($content);

        if (!rename($tempFile, $filePath)) {
            throw new FailedOverwriteException($filePath);
        }
    }
}
