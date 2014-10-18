<?php

namespace phpDocumentor\DocBlock;

use phpDocumentor\Descriptor\DocBlock;

class Parser
{
    /**
     * @var Lexer
     */
    private $lexer;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse($docBlock)
    {
        $tokens = $this->lexer->lex($docBlock);

        $docBlockObject = new DocBlock();
        $iterator = new \ArrayIterator($tokens);
        while($iterator->valid()) {
            $token = $iterator->current();

            switch ($token['type']) {
                case Lexer::T_SUMMARY:
                    $docBlockObject->setSummary($token['value']);
                    break;
            }

            $iterator->next();
        }

        return $docBlockObject;
    }
}