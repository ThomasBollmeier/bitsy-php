<?php

namespace tbollmeier\bitsy;

use tbollmeier\parsian\output\Ast;


class CodeGenerator
{
    private $indent = 0;
    private $indentSize = 4;
    private $symbols;    
    private $out;

    public function generateCode(Ast $ast, $symbolTab, IOutput $out) 
    {
        $this->symbols = $symbolTab;
        $this->out = $out;
        $this->printProgram($ast);
    }

    private function printProgram(Ast $ast)
    {
        // init unassigned variables
        foreach ($this->symbols as $varName => $assigned) {
            if (!$assigned) {
                $this->println("\${$varName} = 0;");
            }
        }
        
        // print statements
        foreach($ast->getChildren() as $stmt) {
            $this->printStatement($stmt);
        }
    }

    private function printStatement(Ast $stmt)
    {
        switch($stmt->getName()) {
            case "if":
                $this->printIf($stmt);
                break;
            case "loop":
                $this->printLoop($stmt);
                break;
            case "break":
                $this->printBreak();
                break;
            case "print":
                $this->printPrint($stmt);
                break;
            case "read":
                $this->printRead($stmt);
                break;
            case "assign":
                $this->printAssignment($stmt);
                break;
        }
    }

    private function printIf(Ast $if) 
    {
        $children = $if->getChildren();
        $cond = $children[0];
        $action = $children[1];
        $alt = count($children) > 2 ? $children[2] : null;

        // Create condition 
        $condExpr = $cond->getChildren()[0];
        $condExprStr = $this->stringExpression($condExpr);
        $checkType = $cond->getAttr("check-type");
        switch ($checkType) {
            case "is-positive":
                $condExprStr .= " > 0";
                break;
            case "is-zero":
                $condExprStr .= " === 0";
                break;
            case "is-negative":
                $condExprStr .= " < 0";
                break;
        }

        $this->println();
        $this->println("if ($condExprStr) {");
        $this->indent();
        foreach ($action->getChildren() as $stmt) {
            $this->printStatement($stmt);
        }
        $this->dedent();
        
        if ($alt !== null) {
            $this->println("} else {");
            $this->indent();
            foreach ($alt->getChildren() as $stmt) {
                $this->printStatement($stmt);
            }
            $this->dedent();
        }

        $this->println("}");
        
    }

    private function printLoop(Ast $loop)
    {
        $this->println();
        $this->println("while (true) {");
        $this->indent();
        foreach ($loop->getChildren() as $stmt) {
            $this->printStatement($stmt);
        }
        $this->dedent();
        $this->println("}");
    }

    private function printBreak() 
    {
        $this->println("break;");
    }

    private function printPrint(Ast $stmt) 
    {
        $expr = $stmt->getChildren()[0];
        $exprStr = $this->stringExpression($expr);

        $this->println("echo {$exprStr} . PHP_EOL;");
    }

    private function printRead(Ast $stmt)
    {
        throw new Exception("READ not implemented (yet)");
    }

    private function printAssignment(Ast $assign)
    {
        list($lhs, $rhs) = $assign->getChildren();
        $varName = $lhs->getText();
        $expr = $this->stringExpression($rhs);
        $this->println("\${$varName} = $expr;");
    }

    private function stringExpression(Ast $expr)
    {
        if ("expression" !== $expr->getName()) {
            return $this->stringTerm($expr);
        } else {
            $ret = "";
            $idx = 0;
            foreach($expr->getChildren() as $child) {
                if (0 === $idx % 2) {
                    $ret .= $this->stringTerm($child);
                } else {
                    $ret .= " " . $this->stringAddOp($child) . " ";
                }
                $idx++;
            }
            return $ret;
        }
    }

    private function stringAddOp(Ast $op) 
    {
        return $op->getName() === "plus" ? "+" : "-";
    }

    private function stringTerm(Ast $term)
    {
        switch ($term->getName()) {
            case "term":
                return $this->stringProduct($term->getChildren());
            case "int":
                $ret = $this->stringInt($term);
                break;
            case "var":
                $ret = $this->stringVar($term);
                break;
            case "group":
                $ret = $this->stringGroup($term);
                break;
            default:
                throw new Exception("Unknown term");
        }

        if ($term->hasAttr("sign")) {
            switch ($term->getAttr("sign")) {
                case "minus":
                    $ret = "( -1 * $ret )";
                    break;
            }
        }

        return $ret;
    }

    private function stringProduct($termsAndOps) 
    {
        if (1 === count($termsAndOps)) {
            return $this->stringTerm($termsAndOps[0]);
        }

        $size = count($termsAndOps);
        $secondOperand = $termsAndOps[$size - 1];
        $op = $termsAndOps[$size - 2];
        $firstPart = array_slice($termsAndOps, 0, -2);
        
        $ret = $this->stringProduct($firstPart);
        $ret .= " " . $this->stringMulOp($op) . " ";
        $ret .= $this->stringTerm($secondOperand);

        if ("div" === $op->getName()) {
            $ret = "intval($ret)"; 
        }

        return $ret;
    }

    private function stringMulOp(Ast $op) 
    {
        return [
            "mult" => "*",
            "div" => "/",
            "mod" => "%"
        ][$op->getName()];
    }

    private function stringInt(Ast $int) 
    {
        return $int->getText();
    }

    private function stringVar(Ast $var) 
    {
        return "\$" . $var->getText();
    }

    private function stringGroup(Ast $group)
    {
        $expr = $group->getChildren()[0];
        return "(" . $this->stringExpression($expr) . ")";
    }

    private function indent()
    {
        $this->indent += $this->indentSize;
    }

    private function dedent()
    {
        $this->indent -= $this->indentSize;
    }

    private function leftpad($text)
    {
        $ret = $text;
        for ($i=0; $i<$this->indent; $i++) {
            $ret = " " . $ret;
        }
        return $ret;
    }

    private function print($text)
    {
        $this->out->write($this->leftpad($text));
    }

    private function println($text="")
    {
        $this->out->writeln($this->leftpad($text));
    }


}