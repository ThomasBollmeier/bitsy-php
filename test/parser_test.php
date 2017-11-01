<?php

require "../vendor/autoload.php";

use tbollmeier\bitsy\Parser;
use tbollmeier\parsian\input\FileCharInput;

$parser = new Parser();
$bitsyFile = "data" . DIRECTORY_SEPARATOR . "division.bitsy";

$ast = $parser->parseFile($bitsyFile);

if ($ast !== false) {
    print $ast->toXml();
} else {
    print $parser->error();
}

