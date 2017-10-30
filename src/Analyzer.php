<?php

namespace tbollmeier\bitsy;

use tbollmeier\parsian\output\Ast;
use tbollmeier\parsian\output\Visitor;


class Analyzer implements Visitor
{
    private $inAssignment;

    public function setupSymbolTab(Ast $ast) 
    {
        $this->symbols = [];
        $this->inAssignment = false;
        
        $ast->accept($this);
    
        return $this->symbols;
    }

    public function enter(Ast $ast)
    {
        switch ($ast->getName()) {
            case "assign":
                $this->inAssignment = true;
                break;
            case "var":
                $varName = $ast->getText();
                if (!array_key_exists($varName, $this->symbols)) {
                    // check if assigned/unassigned before first usage
                    $this->symbols[$varName] = $this->inAssignment;
                }
                if ($this->inAssignment) {
                    $this->inAssignment = false;
                }
                break;
        }
    }

    public function leave(Ast $ast) {}

}