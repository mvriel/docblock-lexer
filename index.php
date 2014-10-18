<?php

use phpDocumentor\DocBlock\Lexer;
use phpDocumentor\DocBlock\Parser;

include ('src/phpDocumentor/DocBlock/Lexer.php');
include ('src/phpDocumentor/DocBlock/Parser.php');
include ('src/phpDocumentor/Descriptor/DocBlock.php');

$comment = <<<DOCCOMMENT
This is a summary.

This is {@internal an } description over
multiple lines.

@ORM\Type(
  name=""
)
@Id()
@see MyTag this is a @description
that spans multiple lines
@internal
@author Mike van Riel <me@mikevanriel.com>
DOCCOMMENT;

$comment = <<<DOCCOMMENT
/**
 * This is a summary.
 *
 * This is {@internal an } description over
 * multiple lines.
 *
 * @ORM\Type(
 *  name=""
 *)
 * @Id()
 * @see MyTag this is a @description
 * that spans multiple lines
 * @internal
 * @author Mike van Riel <me@mikevanriel.com>
 */
DOCCOMMENT;

$lexer = new Lexer();

$tokens = $lexer->lex($comment);
foreach ($tokens as $token) {
    echo $lexer->getTokenName($token['type']) . ': ' . $token['value'] . PHP_EOL;
}

$parser = new Parser($lexer);
var_dump($parser->parse($comment));



$start = microtime(true);
for ($i=0;$i<10000;$i++) {
    $lexer->lex($comment);
}
$endTime = microtime(true) - $start;
var_dump($endTime . 's');