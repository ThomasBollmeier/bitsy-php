<?php

require __DIR__ . "/../vendor/autoload.php";

use tbollmeier\bitsy\Compiler;
use tbollmeier\bitsy\StringOut;

if (2 !== count($argv)) {
    $script = basename($argv[0]);
    echo "Syntax {$script} <path_to_bitsy_file>" . PHP_EOL;
    exit(1);
}

$bitsyFile = $argv[1];

$compiler = new Compiler();
$out = new StringOut();

$out->open();
$ok = $compiler->compile($bitsyFile, $out);
$out->close();

if ($ok) {
    $phpCode = $out->getContent();
    eval($phpCode);
} else {
    echo $compiler->getError();
}

