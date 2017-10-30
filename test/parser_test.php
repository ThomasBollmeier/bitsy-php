<?php

require "../vendor/autoload.php";

use tbollmeier\bitsy\Parser;

$parser = new Parser();
$bitsyFile = "data\\fibonacci.bitsy";

$ast = $parser->parseFile($bitsyFile);

echo $parser->error();

if ($ast !== false) {
    print $ast->toXml();
} else {
    print $parser->error();
}
