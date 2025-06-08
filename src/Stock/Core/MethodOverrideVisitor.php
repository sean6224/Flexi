<?php
namespace Flexi\Stock\Core;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

/**
 * Visitor for overriding class method bodies and parameters.
 *
 * This class modifies method bodies and parameters in a PHP class
 * based on provided override definitions.
 */
class MethodOverrideVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly array $overrides
    ) {}

    public function leaveNode(Node $node): void
    {
        if ($node instanceof ClassMethod)
        {
            $methodName = $node->name->toString();
            if (isset($this->overrides[$methodName]))
            {
                $override = $this->overrides[$methodName];

                $newMethodBody = $this->parseMethodBody($override['body']);
                $newParams = $this->parseParameters($override['parameters']);
                
                $node->params = $newParams;
                $node->stmts = $newMethodBody;
            }
        }
    }

    private function parseMethodBody(string $newMethodBody): array
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->createForHostVersion();

        $stmts = $parser->parse("<?php $newMethodBody");

        if (!is_array($stmts))
        {
            echo "Error parsing method body: $newMethodBody\n";
            return [];
        }

        $methodBody = [];
        foreach ($stmts as $stmt)
        {
            if ($stmt instanceof Stmt) {
                $methodBody[] = $stmt;
            }
        }
        return $methodBody; 
    }

    private function parseParameters(array $parameterDetails): array
    {
        $params = [];
        
        foreach ($parameterDetails as $param) 
        {
            $paramNode = new Param(
                new Variable($param['name']),
                null,
                null,
                false
            );
            
            if ($param['type'] !== 'mixed') {
                $paramNode->type = new Name($param['type']);
            }

            $params[] = $paramNode;
        }

        return $params;
    }
}