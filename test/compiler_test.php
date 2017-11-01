<?php

require "../vendor/autoload.php";

use tbollmeier\bitsy\Compiler;
use tbollmeier\bitsy\StringOut;

$compiler = new Compiler();
//$bitsyFile = "data\\fibonacci.bitsy";
//$bitsyFile = "data\\primes.bitsy";
$bitsyFile = "data\\if_positivie.bitsy";

$out = new StringOut();
$out->open();
$ok = $compiler->compile($bitsyFile, $out);
$out->close();

if ($ok) {
    $phpCode = $out->getContent();
    echo $phpCode;
    eval($phpCode);
} else {
    echo $compiler->getError();
}

