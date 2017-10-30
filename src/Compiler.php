<?php

namespace tbollmeier\bitsy;

use tbollmeier\parsian\output\Ast;


class Compiler
{
    private $error;

    public function compile(string $bitsyFile, IOutput $out) 
    {
        $this->error = "";
        
        $parser = new Parser();
        $ast = $parser->parseFile($bitsyFile);
        if ($ast === false) {
            return false;
        }

        $analyzer = new Analyzer();
        $symbolTab = $analyzer->setupSymbolTab($ast);

        $generator = new CodeGenerator();
        $generator->generateCode($ast, $symbolTab, $out);

        return true;
    }

    public function getError() 
    {
        return $this->error;
    }

}