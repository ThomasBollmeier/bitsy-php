<?php

require "../vendor/autoload.php";

use tbollmeier\bitsy\Parser;
use tbollmeier\parsian\input\FileCharInput;

$parser = new Parser();
$bitsyFile = "data" . DIRECTORY_SEPARATOR . "division.bitsy";

$lexer = $parser->getLexer();
$tokenIn = $lexer->createTokenInput(new FileCharInput($bitsyFile));

$tokenIn->open();
while ($tokenIn->hasMoreTokens()) {
    $token = $tokenIn->nextToken();
    echo $token . PHP_EOL;
}
$tokenIn->close();
